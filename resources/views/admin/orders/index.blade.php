@extends('layouts.app')

@content('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 animate-on-scroll">
        <h2 class="fw-bold mb-0">📦 Manajemen Pesanan</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-up shadow-sm">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden animate-on-scroll">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-gradient-primary text-white">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Pembeli</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="align-middle">
                            <td class="ps-4 fw-semibold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $order->payment_method === 'transfer' ? '🏦 Transfer' : ($order->payment_method === 'cod' ? '📦 COD' : '📱 QRIS') }}
                                </span>
                            </td>
                            <td>
                                @if($order->status === 'Success')
                                    <span class="badge bg-success">Success</span>
                                @elseif($order->status === 'Cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="pe-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary btn-sm rounded-3 px-3">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5"><h5 class="text-muted">Belum ada pesanan</h5></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endcontent
