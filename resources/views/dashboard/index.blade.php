@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        .stat-card {
            transition: all 0.3s ease;
            border: none;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(108,92,231,0.08);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(108,92,231,0.08);
            color: #6c5ce7;
        }
        .quick-action-card {
            transition: all 0.3s;
            cursor: pointer;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
        }
        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(108,92,231,0.08);
            border-color: var(--primary);
        }
        .quick-action-card i {
            font-size: 2rem;
            color: var(--primary);
        }
        .chart-container {
            position: relative;
            height: 250px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Dashboard</h3>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}! Here's your overview.</p>
        </div>
        <a href="{{ route('forms.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-circle me-1"></i> New Form
        </a>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card glass-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3"><i class="bi bi-file-earmark-text fs-4"></i></div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $totalForms }}</h5>
                        <small class="text-muted">Total Forms</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card glass-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="background:rgba(34,197,94,0.08); color:#22c55e;"><i class="bi bi-reply-all fs-4"></i></div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $totalSubmissions }}</h5>
                        <small class="text-muted">Submissions</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card glass-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="background:rgba(251,191,36,0.08); color:#fbbf24;"><i class="bi bi-eye fs-4"></i></div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ number_format($totalViews) }}</h5>
                        <small class="text-muted">Views</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card glass-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="background:rgba(99,102,241,0.08); color:#6366f1;"><i class="bi bi-percent fs-4"></i></div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $completionRate }}%</h5>
                        <small class="text-muted">Completion Rate</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions + AI Templates -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="{{ route('forms.create') }}" class="quick-action-card d-block text-decoration-none">
                <i class="bi bi-plus-circle"></i>
                <h6 class="mt-2">New Form</h6>
                <small class="text-muted">Start from scratch</small>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('templates.index') }}" class="quick-action-card d-block text-decoration-none">
                <i class="bi bi-grid"></i>
                <h6 class="mt-2">AI Templates</h6>
                <small class="text-muted">Pre‑built forms</small>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('submissions.index') }}" class="quick-action-card d-block text-decoration-none">
                <i class="bi bi-table"></i>
                <h6 class="mt-2">View Submissions</h6>
                <small class="text-muted">Recent responses</small>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('analytics') }}" class="quick-action-card d-block text-decoration-none">
                <i class="bi bi-bar-chart"></i>
                <h6 class="mt-2">Analytics</h6>
                <small class="text-muted">Deep insights</small>
            </a>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="glass p-4 rounded-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Submissions (Last 7 days)</h6>
                <div class="chart-container">
                    <canvas id="submissionsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="glass p-4 rounded-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2 text-primary"></i>Form Popularity</h6>
                <div class="chart-container">
                    <canvas id="formPopularityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Forms -->
    <div class="glass p-4 rounded-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Forms</h6>
            <a href="{{ route('forms.index') }}" class="text-primary text-decoration-none small">View All →</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Submissions</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentForms as $form)
                        <tr>
                            <td>{{ $form->title }}</td>
                            <td>{{ $form->submissions_count ?? 0 }}</td>
                            <td>{{ $form->created_at->diffForHumans() }}</td>
                            <td>
                                <span class="badge {{ $form->is_published ? 'bg-success' : 'bg-warning' }}">
                                    {{ $form->is_published ? 'Active' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('forms.edit', $form->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">Edit</a>
                                <a href="{{ route('public.form', $form->shareable_link) }}" class="btn btn-sm btn-outline-secondary rounded-pill" target="_blank">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-2 d-block"></i>
                                No forms yet. <a href="{{ route('forms.create') }}">Create your first form</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trend Chart (Line)
        const ctx = document.getElementById('submissionsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Submissions',
                    data: @json($chartData),
                    borderColor: '#6C63FF',
                    backgroundColor: 'rgba(108,99,255,0.08)',
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#6C63FF',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Doughnut Chart (Form Popularity)
        const pieCtx = document.getElementById('formPopularityChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: @json($formLabels),
                datasets: [{
                    data: @json($formData),
                    backgroundColor: ['#6C63FF', '#8B83FF', '#A29BFE', '#B8B0FF', '#D0C8FF'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                },
                cutout: '70%',
            }
        });
    });
</script>
@endpush