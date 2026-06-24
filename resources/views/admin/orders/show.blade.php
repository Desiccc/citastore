@extends('layouts.app')

@content('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 animate-on-scroll">
        <h2 class="fw-bold mb-0">📦 Detail Pesanan #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-3 px-4">← Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-up shadow-sm">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden animate-on-scroll">
                <div class="card-header bg-gradient-primary text-white fw-semibold py-3 border-0">🛒 Item Pesanan</div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th class="pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $item->product->name }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="pe-4 fw-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 text-end py-3">
                    <h4 class="fw-bold mb-0">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden animate-on-scroll mb-4">
                <div class="card-header bg-gradient-primary text-white fw-semibold py-3 border-0">👤 Informasi Pembeli</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nama:</strong> {{ $order->user->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p class="mb-1"><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden animate-on-scroll mb-4">
                <div class="card-header bg-gradient-primary text-white fw-semibold py-3 border-0">💳 Pembayaran</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Metode:</strong> {{ $order->payment_method === 'transfer' ? '🏦 Transfer Bank' : ($order->payment_method === 'cod' ? '📦 COD' : '📱 QRIS') }}</p>
                    <p class="mb-1"><strong>Status:</strong>
                        @if($order->status === 'Success')
                            <span class="badge bg-success">Success</span>
                        @elseif($order->status === 'Cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($order->shipping_address)
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden animate-on-scroll mb-4">
                <div class="card-header bg-gradient-primary text-white fw-semibold py-3 border-0">📍 Alamat Pengiriman</div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $order->shipping_address }}</p>
                </div>
            </div>
            @endif

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden animate-on-scroll">
                <div class="card-header bg-gradient-primary text-white fw-semibold py-3 border-0">🔄 Ubah Status</div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" class="form-select mb-3" required>
                            <option value="Pending" {{ $order->status === 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="Success" {{ $order->status === 'Success' ? 'selected' : '' }}>✅ Success</option>
                            <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold">Perbarui Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endcontent
