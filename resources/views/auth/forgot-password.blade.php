@extends('layouts.app')

@section('auth-content')
<div style="min-height:100vh;background:linear-gradient(135deg,#f5f0ff 0%,#e0d6ff 100%);display:flex;align-items:center;justify-content:center;padding:1rem;">
    <div class="glass p-5" style="max-width:420px;width:100%;">
        <div style="text-align:center;margin-bottom:1.8rem;">
            <div style="width:56px;height:56px;border-radius:20px;background:linear-gradient(135deg,#6C63FF,#3A2D9A);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:white;margin:0 auto 0.8rem;box-shadow:0 12px 30px rgba(108,99,255,0.3);">F</div>
            <h4 class="fw-bold">Reset Password</h4>
            <p class="text-muted small">Enter your email to receive a reset link.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small">Email Address</label>
                <input type="email" name="email" class="form-control form-control-lg rounded-pill @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@company.com" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill">Send Reset Link</button>
        </form>

        <p class="text-center mt-4 mb-0 small">
            <a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a>
        </p>
    </div>
</div>
@endsection