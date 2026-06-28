@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4 overflow-hidden animate-on-scroll">
                <div class="card-header bg-warning text-dark fw-bold fs-5 py-3 border-0">✏️ Edit Kategori</div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Kategori</label>
                            <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-3 px-4">Kembali</a>
                            <button type="submit" class="btn btn-warning rounded-3 px-4 fw-semibold">🔄 Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
