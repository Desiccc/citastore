<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Belanja Mudah & Aman</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:wght@300;400;600;700;800" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <style>
        :root{--primary:#4f46e5;--secondary:#64748b;--success:#10b981;--info:#06b6d4;--warning:#f59e0b;--danger:#ef4444;--dark:#1e293b}
        body{background:#f1f5f9;font-family:'Nunito',sans-serif;font-size:.9rem;line-height:1.6}
        .bg-gradient-primary{background:linear-gradient(135deg,#667eea,#764ba2)}
        .bg-gradient-hero{background:linear-gradient(135deg,#1e293b,#334155 50%,#4f46e5)}
        .bg-gradient-success{background:linear-gradient(135deg,#10b981,#047857)}
        .bg-gradient-warm{background:linear-gradient(135deg,#f59e0b,#ef4444)}
        .text-gradient{background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .card{transition:transform .2s ease,box-shadow .2s ease;border:none}
        .card:hover{transform:translateY(-4px);box-shadow:0 12px 24px rgba(0,0,0,.12)!important}
        .card-img-top{transition:transform .3s ease;height:200px;object-fit:cover}
        .card:hover .card-img-top{transform:scale(1.05)}
        .navbar{backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px)}
        .navbar .nav-link{position:relative;font-weight:500}
        .navbar .nav-link::after{content:'';position:absolute;bottom:0;left:50%;transform:translateX(-50%) scaleX(0);width:60%;height:2px;background:currentColor;transition:transform .2s ease}
        .navbar .nav-link:hover::after{transform:translateX(-50%) scaleX(1)}
        footer{background:#1e293b;color:#94a3b8}
        .hero-section{position:relative;overflow:hidden}
        .form-control,.form-select{border-radius:.5rem;border:1.5px solid #e2e8f0;transition:all .2s ease}
        .form-control:focus,.form-select:focus{border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.15)}
        .btn{transition:all .2s ease}
        .btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.15)}
        .table{border-radius:.5rem;overflow:hidden}
        .table thead th{border-bottom:none;font-weight:600;text-transform:uppercase;font-size:.75rem;letter-spacing:.05em}
        .badge{padding:.35em .65em;font-weight:500}
        .list-group-item{border:none;margin-bottom:.25rem;border-radius:.5rem!important;transition:all .2s ease}
        .list-group-item:hover{background:#eef2ff;transform:translateX(4px)}
        .animate-fade-in{animation:fadeIn .6s ease forwards}
        .animate-slide-up{animation:slideUp .5s ease forwards}
        @keyframes fadeIn{from{opacity:0}to{opacity:1}}
        @keyframes slideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
    </style>
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
