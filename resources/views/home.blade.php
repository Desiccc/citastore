@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-4 overflow-hidden animate-on-scroll">
                <div class="card-header bg-gradient-primary text-white fw-bold fs-5 py-3 border-0">
                    📊 {{ __('Dashboard') }}
                </div>
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <h5 class="mb-3">Selamat datang, <strong>{{ Auth::user()->name }}</strong>! 👋</h5>
                    <p class="text-muted">{{ __('You are logged in!') }}</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-3 px-4">🛍️ Belanja Sekarang</a>
                        <a href="{{ route('shop.cart') }}" class="btn btn-outline-primary rounded-3 px-4">🛒 Lihat Keranjang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
