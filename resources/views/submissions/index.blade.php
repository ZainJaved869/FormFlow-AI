@extends('layouts.app')

@section('title', 'Submissions')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><i class="bi bi-table me-2" style="color:#6C63FF;"></i>Submissions</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('submissions.export', 'csv') }}" class="btn btn-outline-primary rounded-pill">
                <i class="bi bi-filetype-csv me-1"></i> CSV
            </a>
            <a href="{{ route('submissions.export', 'excel') }}" class="btn btn-outline-success rounded-pill">
                <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </a>
            <a href="{{ route('submissions.export', 'pdf') }}" class="btn btn-outline-danger rounded-pill">
                <i class="bi bi-file-pdf me-1"></i> PDF
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card card-premium p-3 mb-4">
        <form method="GET" action="{{ route('submissions.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Form</label>
                <select name="form_id" class="form-select rounded-pill">
                    <option value="">All Forms</option>
                    @foreach($forms as $form)
                        <option value="{{ $form->id }}" {{ request('form_id') == $form->id ? 'selected' : '' }}>
                            {{ $form->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">From</label>
                <input type="date" name="from" class="form-control rounded-pill" value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">To</label>
                <input type="date" name="to" class="form-control rounded-pill" value="{{ request('to') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Search</label>
                <input type="text" name="search" class="form-control rounded-pill" placeholder="Search in data..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 rounded-pill"><i class="bi bi-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>

    <!-- Submissions Table -->
    <div class="card card-premium p-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Form</th>
                        <th>User</th>
                        <th>Data</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $sub)
                        <tr>
                            <td>{{ $sub->id }}</td>
                            <td>{{ $sub->form->title }}</td>
                            <td>{{ $sub->user->name ?? 'Guest' }}</td>
                            <td>
                                @php
                                    $fieldLabels = $sub->form->fields->pluck('label', 'id')->toArray();
                                    $displayData = collect($sub->data)->map(function ($value, $key) use ($fieldLabels) {
                                        $label = $fieldLabels[$key] ?? 'Field ' . $key;
                                        return $label . ': ' . $value;
                                    })->implode(', ');
                                @endphp
                                <span class="small text-muted" title="{{ $displayData }}">
                                    {{ Str::limit($displayData, 60) }}
                                </span>
                            </td>
                            <td>{{ $sub->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('submissions.show', $sub->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block"></i>
                                No submissions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $submissions->links() }}
    </div>
</div>
@endsection