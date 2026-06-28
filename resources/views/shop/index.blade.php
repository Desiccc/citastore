@extends('layouts.app')

@section('content')
<div class="hero-section bg-gradient-hero text-white py-5 mb-5">
    <div class="container position-relative" style="z-index: 1;">
        <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner text-center">
                <div class="carousel-item active py-5">
                    <h1 class="display-4 fw-bold animate-fade-in">Selamat Datang di {{ config('app.name') }}</h1>
                    <p class="lead fs-5 mb-0 text-light">Dapatkan penawaran harga terbaik untuk produk berkualitas tinggi.</p>
                </div>
                <div class="carousel-item py-5">
                    <h1 class="display-4 fw-bold animate-fade-in">Diskon Kilat Akhir Semester!</h1>
                    <p class="lead fs-5 mb-0 text-light">Gunakan kode promo <span class="badge bg-warning text-dark fs-6">TUGASWEB</span> untuk potongan 10%.</p>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</div>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-up shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate-slide-up shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white fw-bold py-3 border-0">
                    📂 Kategori
                </div>
                <div class="card-body p-2">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('shop.index') }}" class="list-group-item list-group-item-action rounded-3 {{ !request('category') && !request('search') ? 'active' : '' }}">
                            Semua Produk
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('shop.index', ['category' => $category->id, 'search' => request('search')]) }}" 
                               class="list-group-item list-group-item-action rounded-3 {{ request('category') == $category->id ? 'active' : '' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <form action="{{ route('shop.index') }}" method="GET" class="mb-4 animate-on-scroll">
                <div class="input-group shadow-sm">
                    <input type="text" name="search" class="form-control form-control-lg rounded-start-4 border-0 bg-white" placeholder="🔍 Cari produk..." value="{{ request('search') }}">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('shop.index', ['category' => request('category')]) }}" class="btn btn-outline-secondary">✕</a>
                    @endif
                </div>
            </form>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0 animate-on-scroll">
                    @if(request('category'))
                        {{ $categories->firstWhere('id', request('category'))->name ?? '' }}
                    @else
                        {{ request('search') ? 'Hasil pencarian: "' . request('search') . '"' : 'Semua Produk' }}
                    @endif
                </h4>
                <small class="text-muted">{{ $products->count() }} produk ditemukan</small>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @forelse($products as $product)
                    <div class="col animate-on-scroll">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                            <div class="position-relative overflow-hidden" style="height: 200px;">
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top h-100 w-100" alt="{{ $product->name }}" style="object-fit: cover;">
                                <span class="badge bg-gradient-primary position-absolute top-0 end-0 m-2">{{ $product->category->name }}</span>
                                @if($product->stock < 1)
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">Habis</span>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <a href="{{ route('shop.show', $product->id) }}" class="text-decoration-none">
                                    <h5 class="card-title fw-bold text-truncate text-dark">{{ $product->name }}</h5>
                                </a>
                                <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fs-5 fw-bold text-gradient">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <small class="text-muted">Stok: {{ $product->stock }}</small>
                                </div>
                                <form action="{{ route('shop.add-to-cart', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold {{ $product->stock < 1 ? 'disabled' : '' }}" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        {{ $product->stock < 1 ? 'Stok Habis' : '🛒 Masukkan Keranjang' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="card shadow-sm border-0 rounded-4 p-5">
                            <h4 class="text-muted mb-2">Produk tidak ditemukan</h4>
                            <p class="text-muted">Maaf, belum ada produk di kategori ini.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary mx-auto w-auto">Lihat Semua Produk</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
