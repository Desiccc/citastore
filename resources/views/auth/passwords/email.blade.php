@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden animate-on-scroll">
                <div class="card-header bg-gradient-primary text-white text-center border-0 py-4">
                    <h4 class="fw-bold mb-0">🔑 Reset Kata Sandi</h4>
                    <small>Masukkan email untuk tautan reset</small>
                </div>
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success shadow-sm" role="alert">{{ session('status') }}</div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="contoh@email.com">
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 fw-bold rounded-3">Kirim Tautan Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
