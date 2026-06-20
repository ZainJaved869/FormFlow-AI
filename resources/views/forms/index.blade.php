@extends('layouts.app')

@section('title', 'My Forms')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">My Forms</h3>
        <a href="{{ route('forms.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle me-1"></i> New Form
        </a>
    </div>

    <div class="row g-4">
        @forelse($forms as $form)
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title fw-bold">{{ $form->title }}</h5>
                            <span class="badge {{ $form->is_published ? 'bg-success' : 'bg-warning' }}">
                                {{ $form->is_published ? 'Active' : 'Draft' }}
                            </span>
                        </div>
                        <p class="text-muted small mb-3">
                            {{ $form->submissions_count ?? 0 }} submissions · {{ $form->created_at->diffForHumans() }}
                        </p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('forms.edit', $form->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">Edit</a>
                            <a href="{{ route('public.form', $form->shareable_link) }}" class="btn btn-sm btn-outline-secondary rounded-pill" target="_blank">View</a>
                            <a href="{{ route('forms.qr', $form->id) }}" class="btn btn-sm btn-outline-info rounded-pill" title="Download QR Code">
                                <i class="bi bi-qr-code"></i>
                            </a>
                            <form method="POST" action="{{ route('forms.destroy', $form->id) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Delete this form?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-file-earmark-plus fs-1 text-muted"></i>
                <p class="text-muted mt-2">You haven’t created any forms yet.</p>
                <a href="{{ route('forms.create') }}" class="btn btn-primary rounded-pill">Create Your First Form</a>
            </div>
        @endforelse
    </div>
</div>
@endsection