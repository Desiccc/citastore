@extends('layouts.app')

@section('content')
<div class="container py-4 animate-fade-in">
    @if(session('success'))
        <div class="alert alert-success text-center shadow-sm animate-slide-up">{{ session('success') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" id="receipt">
                <div class="card-header bg-gradient-success text-white text-center border-0 py-4">
                    <h4 class="mb-0 fw-bold">✅ Pembelian Berhasil</h4>
                    <small>Terima kasih telah berbelanja</small>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">No. Order</small>
                            <h5 class="fw-bold mb-0">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h5>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted">Tanggal</small>
                            <h6 class="mb-0">{{ $order->created_at->format('d/m/Y H:i') }}</h6>
                        </div>
                    </div>

                    <hr>

                    <table class="table table-borderless small mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Total Belanja</h4>
                        <h4 class="mb-0 fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                    </div>

                    <div class="bg-light rounded-3 p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Metode Pembayaran</span>
                            <strong>{{ $order->payment_method === 'transfer' ? '🏦 Transfer Bank' : ($order->payment_method === 'cod' ? '📦 COD' : '📱 QRIS') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Alamat Pengiriman</span>
                            <small class="text-end ms-3" style="max-width: 250px;">{{ $order->shipping_address }}</small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status</span>
                            <span class="badge bg-warning text-dark fs-6 px-3">{{ $order->status }}</span>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-muted small mb-0">Barang akan diproses dan dikirim segera. Simpan struk ini sebagai bukti pembelian.</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 d-flex justify-content-center gap-3">
                <button class="btn btn-primary rounded-3 px-4 fw-semibold" onclick="window.print()">🖨️ Cetak Struk</button>
                <a href="{{ route('shop.index') }}" class="btn btn-outline-primary rounded-3 px-4 fw-semibold">🛍️ Belanja Lagi</a>
            </div>
        </div>
    </div>
</div>
@endsection
