@extends('layouts.app')

@section('title', 'Analytics')

@section('content')
<div class="container-fluid px-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-bar-chart me-2" style="color:#6C63FF;"></i>Analytics</h4>
    <p class="text-muted">Deep insights into your form performance.</p>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="glass p-3 text-center rounded-4">
                <h5 class="text-muted">Total Forms</h5>
                <h2 class="fw-bold">{{ $totalForms ?? 0 }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass p-3 text-center rounded-4">
                <h5 class="text-muted">Submissions</h5>
                <h2 class="fw-bold">{{ $totalSubmissions ?? 0 }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass p-3 text-center rounded-4">
                <h5 class="text-muted">Completion Rate</h5>
                <h2 class="fw-bold">{{ $completionRate ?? 0 }}%</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass p-3 text-center rounded-4">
                <h5 class="text-muted">Avg. Rating</h5>
                <h2 class="fw-bold">4.8 ★</h2>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="glass p-4 rounded-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-graph-up me-2 text-primary"></i>Submissions (Last 30 Days)</h6>
                <div style="height:300px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="glass p-4 rounded-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2 text-primary"></i>Form Popularity</h6>
                <div style="height:280px;">
                    <canvas id="formChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Breakdown + Recent Activity -->
    <div class="row g-4 mt-2">
        <div class="col-lg-6">
            <div class="glass p-4 rounded-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-device-desktop me-2 text-primary"></i>Device Breakdown</h6>
                <div style="height:250px;">
                    <canvas id="deviceChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="glass p-4 rounded-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Activity</h6>
                <div class="list-group list-group-flush bg-transparent">
                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-0">
                        <span><i class="bi bi-reply-all text-primary me-2"></i>New submission on "Job Application"</span>
                        <small class="text-muted">2 min ago</small>
                    </div>
                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-0">
                        <span><i class="bi bi-reply-all text-primary me-2"></i>New submission on "Feedback Survey"</span>
                        <small class="text-muted">1 hour ago</small>
                    </div>
                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-0">
                        <span><i class="bi bi-pencil text-primary me-2"></i>Form "Student Admission" published</span>
                        <small class="text-muted">3 hours ago</small>
                    </div>
                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-0">
                        <span><i class="bi bi-robot text-primary me-2"></i>AI generated form "Customer Feedback"</span>
                        <small class="text-muted">Yesterday</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Trend Chart (Line)
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: [{
                label: 'Submissions',
                data: @json($chartData ?? []),
                borderColor: '#6C63FF',
                backgroundColor: 'rgba(108,99,255,0.08)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#6C63FF',
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Form Popularity (Doughnut)
    const formCtx = document.getElementById('formChart').getContext('2d');
    new Chart(formCtx, {
        type: 'doughnut',
        data: {
            labels: @json($formLabels ?? []),
            datasets: [{
                data: @json($formData ?? []),
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
            cutout: '65%',
        }
    });

    // Device Chart (Doughnut)
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Desktop', 'Mobile', 'Tablet'],
            datasets: [{
                data: [55, 30, 15],
                backgroundColor: ['#6C63FF', '#8B83FF', '#A29BFE'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            },
            cutout: '65%',
        }
    });
});
</script>
@endpush