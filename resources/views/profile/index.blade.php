@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-premium p-4">
                <h4 class="fw-bold"><i class="bi bi-person-circle me-2" style="color:#6C63FF;"></i>Profile Settings</h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Full Name</label>
                        <input type="text" name="name" class="form-control rounded-pill @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-pill @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control rounded-pill @error('password') is-invalid @enderror" placeholder="••••••••">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-pill" placeholder="••••••••">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Current Plan</label>
                        <div class="p-3 bg-light rounded-3 text-center">{{ ucfirst($user->subscription_plan ?? 'Free') }}</div>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill">Save Changes</button>
                    <!-- Removed the "Manage Subscription" link -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection