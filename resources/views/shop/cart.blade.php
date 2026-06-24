@extends('layouts.app')

@content('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4 animate-on-scroll">🛒 Keranjang Belanja</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-up shadow-sm" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-gradient-primary text-white">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Harga</th>
                                <th style="width: 100px;">Jumlah</th>
                                <th>Subtotal</th>
                                <th class="pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @forelse($cartItems as $item)
                                @php 
                                    $subtotal = $item->product->price * $item->quantity; 
                                    $total += $subtotal;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset('storage/' . $item->product->image) }}" width="60" height="60" class="rounded-3" style="object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $item->product->name }}</h6>
                                                <small class="text-muted">{{ $item->product->category->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fw-semibold">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('shop.update-cart', $item->id) }}" method="POST" class="d-flex align-items-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 fw-bold" onclick="this.closest('form').querySelector('input[name=quantity]').stepDown(); this.closest('form').submit();" {{ $item->quantity <= 1 ? 'disabled' : '' }}>−</button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock + $item->quantity }}" readonly class="form-control text-center border-0 bg-light fw-bold" style="width: 50px;">
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 fw-bold" onclick="this.closest('form').querySelector('input[name=quantity]').stepUp(); this.closest('form').submit();" {{ $item->quantity >= $item->product->stock + $item->quantity ? 'disabled' : '' }}>+</button>
                                        </form>
                                    </td>
                                    <td class="fw-bold text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    <td class="pe-4">
                                        <div class="d-flex gap-1">
                                            <form action="{{ route('shop.remove-from-cart', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-3" onclick="return confirm('Hapus item ini?')">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <h5 class="text-muted mb-2">Keranjang masih kosong</h5>
                                            <p class="text-muted mb-3">Ayo mulai belanja!</p>
                                            <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-3 px-4">🛍️ Belanja Sekarang</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(count($cartItems) > 0)
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 bg-gradient-primary text-white animate-on-scroll">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">📋 Ringkasan Belanja</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Item</span>
                        <strong>{{ count($cartItems) }} item</strong>
                    </div>
                    <hr class="border-light opacity-25">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5">Total Harga</span>
                        <strong class="fs-4">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    
                    <form action="{{ route('shop.checkout') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">📮 Alamat Pengiriman</label>
                            <textarea name="shipping_address" class="form-control rounded-3 border-0" rows="3" placeholder="Nama penerima, alamat lengkap, kota, kode pos, no. telepon" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <select name="payment_method" class="form-select rounded-3 border-0" required>
                                <option value="">— Pilih —</option>
                                <option value="transfer">🏦 Transfer Bank</option>
                                <option value="cod">📦 COD (Bayar di Tempat)</option>
                                <option value="qris">📱 QRIS (GoPay/OVO/Dana)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-light text-primary fw-bold w-100 py-2 rounded-3 fs-5">
                            ✅ Proses Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endcontent
