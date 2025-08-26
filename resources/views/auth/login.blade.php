@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-4">
        <div class="card shadow-sm mb-1 border-0 bg-primary text-white">
        <div class="text-center mb-4 mt-4">
            <h2 class="mt-2 fw-bold text-white">Perpustakaan App</h2>
            <p class="text-muted-white ">Silakan masuk untuk melanjutkan</p>
        </div>
        </div>

        {{-- PERUBAHAN DI SINI: Menambahkan class bg-primary dan text-white --}}
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Alamat Email') }}</label>
                        <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Ingat Saya') }}
                            </label>
                        </div>
                    </div>

                    <div class="d-grid">
                        {{-- Tombol Login sekarang menggunakan style 'light' agar kontras --}}
                        <button type="submit" class="btn btn-light btn-lg text-primary fw-bold">
                            {{ __('Login') }}
                        </button>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            {{-- Link lupa password diubah warnanya menjadi putih --}}
                            <a class="btn btn-link text-white text-decoration-none" href="{{ route('password.request') }}">
                                {{ __('Lupa Password Anda?') }}
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
