<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Belanja Mudah & Aman</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:wght@300;400;600;700;800" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-gradient-primary shadow-lg sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold fs-4" href="{{ url('/') }}">
                    🛍️ {{ config('app.name') }}
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('shop.index') }}">Katalog</a>
                        </li>
                        @auth
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('shop.cart') }}">Keranjang 🛒</a>
                        </li>
                        @if(Auth::user()->role === 'admin')
                        <li class="nav-item dropdown">
                            <a class="nav-link px-3 fw-bold text-warning dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Admin</a>
                            <ul class="dropdown-menu border-0 shadow-lg rounded-3 mt-2">
                                <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">📦 Produk</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">📂 Kategori</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">📋 Pesanan</a></li>
                            </ul>
                        </li>
                        @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link px-3" href="{{ route('login') }}">Masuk</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-light btn-sm fw-bold px-3 ms-2" href="{{ route('register') }}">Daftar</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="badge bg-light text-dark rounded-pill px-2 py-1">{{ Auth::user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 mt-2">
                                    <a class="dropdown-item" href="{{ route('home') }}">Dashboard</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Keluar
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

        <footer class="mt-5 pt-5 pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <h5 class="text-white fw-bold">🛍️ {{ config('app.name') }}</h5>
                        <p class="small">Toko online terpercaya dengan produk berkualitas dan harga terbaik. Belanja mudah, aman, dan nyaman.</p>
                    </div>
                    <div class="col-md-2 mb-4">
                        <h6 class="text-white fw-semibold">Navigasi</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-1"><a href="{{ route('shop.index') }}" class="text-decoration-none">Katalog</a></li>
                            <li class="mb-1"><a href="{{ route('shop.cart') }}" class="text-decoration-none">Keranjang</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4">
                        <h6 class="text-white fw-semibold">Kontak</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-1">📧 info@toko-online.test</li>
                            <li class="mb-1">📞 0812-3456-7890</li>
                            <li class="mb-1">📍 Jakarta, Indonesia</li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4">
                        <h6 class="text-white fw-semibold">Ikuti Kami</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle">FB</a>
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle">IG</a>
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle">TW</a>
                        </div>
                    </div>
                </div>
                <hr class="border-secondary">
                <div class="text-center small">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Dibuat dengan ❤️ untuk tugas pemrograman web.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
