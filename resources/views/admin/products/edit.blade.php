@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-4 overflow-hidden animate-on-scroll">
                <div class="card-header bg-warning text-dark fw-bold fs-5 py-3 border-0">
                    ✏️ Edit Produk
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Produk</label>
                            <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" required>{{ $product->description }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Harga (Rp)</label>
                                <input type="number" name="price" value="{{ $product->price }}" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Stok</label>
                                <input type="number" name="stock" value="{{ $product->stock }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Foto Produk Baru</label>
                            <input type="file" name="image" class="form-control">
                            <div class="form-text">Kosongkan jika tidak ingin mengganti foto.</div>
                            <div class="mt-2">
                                <small class="text-muted">Foto saat ini:</small>
                                <img src="{{ $product->image_url }}" alt="img" width="80" height="80" class="rounded-3 mt-1" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-3 px-4">Kembali</a>
                            <button type="submit" class="btn btn-warning rounded-3 px-4 fw-semibold">🔄 Perbarui Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
