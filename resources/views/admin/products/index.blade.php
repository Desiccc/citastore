@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 animate-on-scroll">
        <h2 class="fw-bold mb-0">📦 Manajemen Produk</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-3 px-4 fw-semibold shadow-sm">+ Tambah Produk</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-up shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden animate-on-scroll">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-gradient-primary text-white">
                    <tr>
                        <th class="ps-4">Foto</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="align-middle">
                            <td class="ps-4">
                                <img src="{{ $product->image_url }}" alt="img" width="60" height="60" class="rounded-3" style="object-fit: cover;">
                            </td>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $product->category->name }}</span></td>
                            <td class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                @if($product->stock > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success">{{ $product->stock }} pcs</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Habis</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm rounded-3 fw-semibold px-3">✏️ Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-3 fw-semibold px-3" onclick="return confirm('Yakin ingin menghapus produk ini?')">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <h5 class="text-muted mb-2">Belum ada produk</h5>
                                    <p class="text-muted mb-3">Tambahkan produk pertama Anda.</p>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-3">+ Tambah Produk</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
