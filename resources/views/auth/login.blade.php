@extends('layouts.app')

@section('auth-content')
<div style="min-height:100vh;background:linear-gradient(135deg,#f5f0ff 0%,#e0d6ff 100%);display:flex;align-items:center;justify-content:center;padding:1rem;">
    <div class="glass p-5" style="max-width:420px;width:100%;">
        <div style="text-align:center;margin-bottom:1.8rem;">
            <div style="width:56px;height:56px;border-radius:20px;background:linear-gradient(135deg,#6C63FF,#3A2D9A);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:white;margin:0 auto 0.8rem;box-shadow:0 12px 30px rgba(108,99,255,0.3);">F</div>
            <h4 class="fw-bold">Welcome Back</h4>
            <p class="text-muted small">Sign in to continue building smart forms.</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small">Email Address</label>
                <input type="email" name="email" class="form-control form-control-lg rounded-pill @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@company.com" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Password</label>
                <input type="password" name="password" class="form-control form-control-lg rounded-pill @error('password') is-invalid @enderror" placeholder="••••••••" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot password?</a>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill">Sign In</button>
        </form>

        <p class="text-center mt-4 mb-0 small">Don’t have an account? <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Create one</a></p>
    </div>
</div>
@endsection