@extends('layouts.app')

@content('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4 overflow-hidden animate-on-scroll">
                <div class="card-header bg-gradient-primary text-white fw-bold fs-5 py-3 border-0">➕ Tambah Kategori</div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Kategori</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Elektronik" required>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-3 px-4">Kembali</a>
                            <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold">💾 Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endcontent
