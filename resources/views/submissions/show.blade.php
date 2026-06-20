@extends('layouts.app')

@section('title', 'Submission #' . $submission->id)

@section('content')
<div class="container">
    <div class="card card-premium p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="fw-bold">Submission #{{ $submission->id }}</h4>
            <a href="{{ route('submissions.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Form:</strong> {{ $submission->form->title }}</p>
                <p><strong>User:</strong> {{ $submission->user->name ?? 'Guest' }}</p>
                <p><strong>Submitted at:</strong> {{ $submission->created_at->toDateTimeString() }}</p>
            </div>
            <div class="col-md-6">
                <div class="bg-light p-3 rounded-3">
                    <h6>Data:</h6>
                    @php
                        $fieldLabels = $submission->form->fields->pluck('label', 'id')->toArray();
                    @endphp
                    <dl class="row mb-0">
                        @foreach($submission->data as $key => $value)
                            <dt class="col-sm-4">{{ $fieldLabels[$key] ?? 'Field ' . $key }}</dt>
                            <dd class="col-sm-8">{{ $value }}</dd>
                        @endforeach
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection