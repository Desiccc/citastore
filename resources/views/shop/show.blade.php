@extends('layouts.app')

@content('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-up shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <nav aria-label="breadcrumb" class="mb-4 animate-on-scroll">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none">Katalog</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category_id]) }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4 mb-5">
        <div class="col-md-6 animate-on-scroll">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-100" style="object-fit: cover; max-height: 450px;">
            </div>
        </div>
        <div class="col-md-6 animate-on-scroll">
            <span class="badge bg-gradient-primary fs-6 mb-3">{{ $product->category->name }}</span>
            <h2 class="fw-bold mb-3">{{ $product->name }}</h2>
            <h3 class="text-gradient fw-bold mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
            <hr>
            <div class="mb-4">
                <h6 class="fw-semibold text-muted">Deskripsi</h6>
                <p class="mb-0">{{ $product->description }}</p>
            </div>
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="fw-semibold">Stok:</span>
                @if($product->stock > 0)
                    <span class="badge bg-success bg-opacity-10 text-success fs-6">{{ $product->stock }} tersedia</span>
                @else
                    <span class="badge bg-danger bg-opacity-10 text-danger fs-6">Stok Habis</span>
                @endif
            </div>

            @auth
                <form action="{{ route('shop.add-to-cart', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-semibold px-5 shadow-sm w-100 w-md-auto" {{ $product->stock < 1 ? 'disabled' : '' }}>
                        🛒 {{ $product->stock < 1 ? 'Stok Habis' : 'Masukkan Keranjang' }}
                    </button>
                </form>
                @if($product->stock > 0)
                    <small class="text-muted mt-2 d-block">✓ Gratis ongkir untuk pembelian minimal Rp 100.000</small>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-3 fw-semibold px-5 shadow-sm">🔐 Login untuk Membeli</a>
            @endauth
        </div>
    </div>

    @if($related->count() > 0)
        <div class="mb-4">
            <h4 class="fw-bold mb-4 animate-on-scroll">Produk Terkait</h4>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                @foreach($related as $item)
                    <div class="col animate-on-scroll">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                            <a href="{{ route('shop.show', $item->id) }}" style="height: 180px;" class="overflow-hidden">
                                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top h-100 w-100" alt="{{ $item->name }}" style="object-fit: cover;">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title fw-bold text-truncate">{{ $item->name }}</h6>
                                <span class="fw-bold text-gradient mb-2">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                <form action="{{ route('shop.add-to-cart', $item->id) }}" method="POST" class="mt-auto">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm w-100 rounded-3" {{ $item->stock < 1 ? 'disabled' : '' }}>
                                        🛒 Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endcontent
