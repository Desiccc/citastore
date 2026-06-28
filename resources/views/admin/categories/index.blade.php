@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 animate-on-scroll">
        <h2 class="fw-bold mb-0">📂 Manajemen Kategori</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-3 px-4 fw-semibold shadow-sm">+ Tambah Kategori</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-up shadow-sm">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate-slide-up shadow-sm">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden animate-on-scroll">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-gradient-primary text-white">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Produk</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="align-middle">
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $category->products_count }} produk</span></td>
                            <td class="pe-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm rounded-3 fw-semibold px-3">✏️ Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-3 fw-semibold px-3" onclick="return confirm('Yakin ingin menghapus kategori ini?')">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-5"><h5 class="text-muted">Belum ada kategori</h5></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
