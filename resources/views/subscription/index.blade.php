@extends('layouts.app')

@section('title', 'Subscription Plans')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold"><i class="bi bi-credit-card me-2" style="color:#6C63FF;"></i>Subscription Plans</h4>
            <p class="text-muted">Choose the plan that fits your needs. Upgrade or downgrade anytime.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        @foreach($plans as $key => $plan)
            <div class="col-md-4">
                <div class="card card-premium h-100 p-4 text-center {{ $currentPlan === $key ? 'border border-primary' : '' }}">
                    @if($currentPlan === $key)
                        <span class="badge bg-primary rounded-pill px-3 py-2 mb-2">Current Plan</span>
                    @endif
                    <h5 class="fw-bold">{{ $plan['name'] }}</h5>
                    <h2 class="fw-bold">{{ $plan['price'] }}<small class="fs-6 text-muted">{{ $plan['period'] }}</small></h2>
                    <ul class="list-unstyled text-start mt-3">
                        @foreach($plan['features'] as $feature)
                            <li><i class="bi bi-check-circle-fill me-2" style="color:#6C63FF;"></i>{{ $feature }}</li>
                        @endforeach
                    </ul>
                    @if($currentPlan === $key)
                        <button class="btn btn-outline-secondary rounded-pill w-100" disabled>Current Plan</button>
                    @elseif($key === 'free')
                        <form method="POST" action="{{ route('subscription.cancel') }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary rounded-pill w-100" onclick="return confirm('Downgrade to Free?')">Downgrade</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('subscription.store') }}">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $key }}">
                            <button type="submit" class="btn btn-primary rounded-pill w-100">Upgrade to {{ $plan['name'] }}</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="card card-premium p-4 mt-4">
        <h6 class="fw-bold">Need more?</h6>
        <p class="text-muted">Contact us for enterprise plans with custom pricing and dedicated support.</p>
        <a href="#" class="btn btn-outline-primary rounded-pill" style="width:fit-content;">Contact Sales</a>
    </div>
</div>

<style>
    .card-premium {
        background: var(--bs-body-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--bs-border-color);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(108,99,255,0.08);
        transition: all 0.3s;
    }
    .card-premium:hover {
        transform: translateY(-6px);
        box-shadow: 0 30px 80px rgba(108,99,255,0.12);
    }
</style>
@endsection