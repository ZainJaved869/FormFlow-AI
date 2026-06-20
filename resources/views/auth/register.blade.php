@extends('layouts.app')

@section('auth-content')
<div style="min-height:100vh;background:linear-gradient(135deg,#f5f0ff 0%,#e0d6ff 100%);display:flex;align-items:center;justify-content:center;padding:1rem;">
    <div class="glass p-5" style="max-width:440px;width:100%;">
        <div style="text-align:center;margin-bottom:1.8rem;">
            <div style="width:56px;height:56px;border-radius:20px;background:linear-gradient(135deg,#6C63FF,#3A2D9A);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:white;margin:0 auto 0.8rem;box-shadow:0 12px 30px rgba(108,99,255,0.3);">F</div>
            <h4 class="fw-bold">Create Account</h4>
            <p class="text-muted small">Start building smart forms today.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small">Full Name</label>
                <input type="text" name="name" class="form-control form-control-lg rounded-pill @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Email Address</label>
                <input type="email" name="email" class="form-control form-control-lg rounded-pill @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@company.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Password</label>
                <input type="password" name="password" class="form-control form-control-lg rounded-pill @error('password') is-invalid @enderror" placeholder="••••••••" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control form-control-lg rounded-pill" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill">Create Account</button>
        </form>

        <p class="text-center mt-4 mb-0 small">Already have an account? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Sign In</a></p>
    </div>
</div>
@endsection