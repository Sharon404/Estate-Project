@extends('layouts.velzon.app')

@section('title', 'Analytics')
@section('page-title', 'Analytics & Reports')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Business Intelligence</p>
                <h1>Analytics Dashboard</h1>
                <p class="lede">Revenue, bookings, and payment trends over the last 30 days.</p>
            </div>
            <div class="pill">Last 30 days</div>
        </div>

        <!-- Monthly Summary Stats -->
        <div class="metrics">
            <div class="card metric">
                <p class="label">Monthly Revenue</p>
                <p class="value">KES {{ number_format($monthlyStats['total_revenue'], 2) }}</p>
                <p class="sub">Successful payments</p>
            </div>
            <div class="card metric">
                <p class="label">Total Bookings</p>
                <p class="value">{{ number_format($monthlyStats['total_bookings']) }}</p>
                <p class="sub">This month</p>
            </div>
            <div class="card metric">
                <p class="label">Avg Booking Value</p>
                <p class="value">KES {{ number_format($monthlyStats['avg_booking_value'], 2) }}</p>
                <p class="sub">Per payment</p>
            </div>
            <div class="card metric">
                <p class="label">Success Rate</p>
                <p class="value">{{ $monthlyStats['success_rate'] }}%</p>
                <p class="sub">Payment completion</p>
            </div>
        </div>

        <div class="grid">
            <!-- Revenue Per Day Chart -->
            <div class="card">
                <div class="card-head">
                    <h3>Revenue Trend</h3>
                    <p class="muted">Daily revenue (last 30 days)</p>
                </div>
                <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Bookings Per Day Chart -->
            <div class="card">
                <div class="card-head">
                    <h3>Bookings Trend</h3>
                    <p class="muted">Daily bookings (last 30 days)</p>
                </div>
                <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="bookingsChart"></canvas>
                </div>
            </div>

            <!-- Payment Success vs Failure -->
            <div class="card">
                <div class="card-head">
                    <h3>Payment Status</h3>
                    <p class="muted">Success vs failure distribution</p>
                </div>
                <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="paymentStatusChart"></canvas>
                </div>
            </div>

            <!-- STK vs C2B Usage -->
            <div class="card">
                <div class="card-head">
                    <h3>Payment Methods</h3>
                    <p class="muted">STK vs C2B usage comparison</p>
                </div>
                <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="methodChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dash-shell { max-width: 1400px; margin: 0 auto; }
        .dash-head { margin-bottom: 2rem; }
        .dash-head h1 { font-size: 2rem; margin: 0.5rem 0; }
        .metrics { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .card.metric { padding: 1.5rem; border: 1px solid #e0e0e0; border-radius: 8px; }
        .card.metric .label { font-size: 0.875rem; color: #666; margin: 0; }
        .card.metric .value { font-size: 1.75rem; font-weight: bold; margin: 0.5rem 0; }
        .card.metric .sub { font-size: 0.75rem; color: #999; margin: 0; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; }
        .card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.5rem; }
        .card-head { margin-bottom: 1rem; }
        .card-head h3 { margin: 0 0 0.25rem 0; font-size: 1.1rem; }
        .card-head .muted { margin: 0; font-size: 0.875rem; color: #999; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Revenue Per Day Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Revenue (KES)',
                    data: {!! json_encode($revenuePerDay->pluck('revenue')->map(function($v) { return $v ? round($v) : 0; })->toArray()) !!},
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Bookings Per Day Chart
        const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
        new Chart(bookingsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Bookings',
                    data: {!! json_encode($bookingsPerDay->pluck('total')->toArray()) !!},
                    backgroundColor: '#2196F3',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Payment Status Chart
        const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
        new Chart(paymentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($paymentSuccessFailed->pluck('status')->toArray()) !!},
                datasets: [{
                    data: {!! json_encode($paymentSuccessFailed->pluck('total')->toArray()) !!},
                    backgroundColor: ['#4CAF50', '#FF9800', '#F44336'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Method Chart
        const methodCtx = document.getElementById('methodChart').getContext('2d');
        new Chart(methodCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($stkVsC2b->pluck('method')->toArray()) !!},
                datasets: [{
                    data: {!! json_encode($stkVsC2b->pluck('total')->toArray()) !!},
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
@endsection
