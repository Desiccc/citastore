<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::query();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(12)->withQueryString();

        return view('shop.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
        return view('shop.show', compact('product', 'related'));
    }

    // Menampilkan halaman Keranjang Belanja
    public function cart()
    {
        // Mengambil data cart milik user yang sedang login beserta data produknya
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        return view('shop.cart', compact('cartItems'));
    }

    // Menambahkan produk ke dalam keranjang
    public function addToCart(Request $request, $productId)
    {
        // Proteksi: Harus login dulu sebelum bisa belanja
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk belanja!');
        }

        $product = Product::findOrFail($productId);

        // Cek apakah produk tersebut sudah ada di keranjang user
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();

        if ($cartItem) {
            // Jika sudah ada, tinggal tambahkan quantity-nya
            $cartItem->increment('quantity');
        } else {
            // Jika belum ada, buat data baru di tabel carts
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', $product->name . ' berhasil dimasukkan ke keranjang!');
    }

    public function updateCart(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $request->validate(['quantity' => 'required|integer|min:1|max:' . ($cartItem->product->stock + $cartItem->quantity)]);

        $cartItem->update(['quantity' => $request->quantity]);
        return redirect()->back()->with('success', 'Jumlah produk berhasil diubah!');
    }

    public function removeFromCart($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cartItem->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang!');
    }

    public function checkout(Request $request)
{
    $request->validate([
        'payment_method' => 'required|string|in:transfer,cod,qris',
        'shipping_address' => 'required|string|min:10|max:500',
    ]);

    $cartItems = Cart::where('user_id', Auth::id())->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('shop.index')->with('error', 'Keranjang belanja Anda kosong!');
    }

    $totalPrice = 0;
    foreach ($cartItems as $item) {
        if ($item->product->stock < $item->quantity) {
            return redirect()->back()->with('error', 'Stok produk ' . $item->product->name . ' tidak mencukupi!');
        }
        $totalPrice += $item->product->price * $item->quantity;
    }

    $order = Order::create([
        'user_id' => Auth::id(),
        'total_price' => $totalPrice,
        'status' => 'Pending',
        'payment_method' => $request->payment_method,
        'shipping_address' => $request->shipping_address,
    ]);

    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ]);

        $item->product->decrement('stock', $item->quantity);
        $item->delete();
    }

    return redirect()->route('shop.receipt', $order->id)->with('success', 'Transaksi Berhasil! Terima kasih telah berbelanja 🎉');
}

public function receipt(Order $order)
{
    if ($order->user_id !== Auth::id()) {
        return redirect()->route('shop.index')->with('error', 'Anda tidak memiliki akses ke struk ini!');
    }

    $order->load('items.product');
    return view('shop.receipt', compact('order'));
}

    }