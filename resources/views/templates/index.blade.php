@extends('layouts.app')

@section('title', 'Form Templates')

@section('content')
<div class="container-fluid px-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-grid me-2" style="color:#6C63FF;"></i>Form Templates</h4>
    <p class="text-muted">Start with a pre-built template and customize it to your needs.</p>

    @if(isset($categories) && $categories->count())
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('templates.index') }}" class="btn btn-outline-primary rounded-pill {{ !request('category') ? 'active' : '' }}">All</a>
            @foreach($categories as $category)
                <a href="{{ route('templates.index', ['category' => $category]) }}" class="btn btn-outline-primary rounded-pill {{ request('category') == $category ? 'active' : '' }}">
                    {{ ucfirst($category) }}
                </a>
            @endforeach
        </div>
    @endif

    <div class="row g-4">
        @forelse($templates as $template)
            <div class="col-md-4 col-lg-3">
                <div class="card card-premium h-100 p-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <i class="{{ $template->icon ?? 'bi bi-file-earmark' }}" style="font-size:2rem;color:#6C63FF;"></i>
                        <div>
                            <h6 class="fw-bold mb-0">{{ $template->name }}</h6>
                            <small class="text-muted">{{ ucfirst($template->category) }}</small>
                        </div>
                    </div>
                    <p class="text-muted small" style="flex:1;">{{ Str::limit($template->description, 80) }}</p>
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('templates.use', $template->slug) }}" class="btn btn-primary btn-sm rounded-pill w-100">Use Template</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-2">No templates available yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection