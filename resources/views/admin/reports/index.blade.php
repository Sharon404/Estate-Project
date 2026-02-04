@extends('layouts.velzon.app')

@section('title', 'Reports Dashboard')
@section('page-title', 'Reports')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Analytics & Reports</p>
                <h1>Reports Dashboard</h1>
                <p class="lede">Comprehensive business analytics and insights</p>
            </div>
        </div>

        <!-- Report Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <!-- Revenue Report -->
            <div class="card" style="cursor: pointer;" onclick="window.location.href='{{ route('admin.reports.revenue') }}'">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="background: #E8F5E9; padding: 1rem; border-radius: 8px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 0.5rem;">Revenue Analysis</h3>
                        <p style="margin: 0; color: #5a5661; font-size: 0.875rem;">Analyze revenue by month, property, and payment method</p>
                        <a href="{{ route('admin.reports.revenue') }}" class="link" style="margin-top: 0.75rem; display: inline-block;">View Report →</a>
                    </div>
                </div>
            </div>

            <!-- Occupancy Report -->
            <div class="card" style="cursor: pointer;" onclick="window.location.href='{{ route('admin.reports.occupancy') }}'">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="background: #E3F2FD; padding: 1rem; border-radius: 8px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="9" y1="3" x2="9" y2="21"></line>
                            <line x1="15" y1="3" x2="15" y2="21"></line>
                        </svg>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 0.5rem;">Occupancy Rates</h3>
                        <p style="margin: 0; color: #5a5661; font-size: 0.875rem;">Property occupancy rates and availability analysis</p>
                        <a href="{{ route('admin.reports.occupancy') }}" class="link" style="margin-top: 0.75rem; display: inline-block;">View Report →</a>
                    </div>
                </div>
            </div>

            <!-- Cancellations Report -->
            <div class="card" style="cursor: pointer;" onclick="window.location.href='{{ route('admin.reports.cancellations') }}'">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="background: #FFF3E0; padding: 1rem; border-radius: 8px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#E65100" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 0.5rem;">Cancellation Analysis</h3>
                        <p style="margin: 0; color: #5a5661; font-size: 0.875rem;">Track cancellation trends and reasons</p>
                        <a href="{{ route('admin.reports.cancellations') }}" class="link" style="margin-top: 0.75rem; display: inline-block;">View Report →</a>
                    </div>
                </div>
            </div>

            <!-- Payment Reconciliation -->
            <div class="card" style="cursor: pointer;" onclick="window.location.href='{{ route('admin.reconciliation.index') }}'">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="background: #F3E5F5; padding: 1rem; border-radius: 8px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7B1FA2" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 0.5rem;">Payment Reconciliation</h3>
                        <p style="margin: 0; color: #5a5661; font-size: 0.875rem;">Identify and resolve payment mismatches</p>
                        <a href="{{ route('admin.reconciliation.index') }}" class="link" style="margin-top: 0.75rem; display: inline-block;">View Report →</a>
                    </div>
                </div>
            </div>

            <!-- Users & KYC -->
            <div class="card" style="cursor: pointer;" onclick="window.location.href='{{ route('admin.users.index') }}'">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="background: #E8F5E9; padding: 1rem; border-radius: 8px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 0.5rem;">Users & KYC</h3>
                        <p style="margin: 0; color: #5a5661; font-size: 0.875rem;">User statistics and KYC verification status</p>
                        <a href="{{ route('admin.users.index') }}" class="link" style="margin-top: 0.75rem; display: inline-block;">View Report →</a>
                    </div>
                </div>
            </div>

            <!-- Properties -->
            <div class="card" style="cursor: pointer;" onclick="window.location.href='{{ route('admin.properties.index') }}'">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="background: #E3F2FD; padding: 1rem; border-radius: 8px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 0.5rem;">Property Insights</h3>
                        <p style="margin: 0; color: #5a5661; font-size: 0.875rem;">Property performance and availability</p>
                        <a href="{{ route('admin.properties.index') }}" class="link" style="margin-top: 0.75rem; display: inline-block;">View Report →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Quick Statistics</h3>
                <p class="muted">Real-time business metrics</p>
            </div>
            <div class="metrics-grid">
                <div class="chip">
                    <p class="metric">{{ $stats['total_bookings'] ?? 0 }}</p>
                    <p class="label">Total Bookings</p>
                </div>
                <div class="chip">
                    <p class="metric">{{ number_format($stats['total_revenue'] ?? 0) }} KES</p>
                    <p class="label">Total Revenue</p>
                </div>
                <div class="chip">
                    <p class="metric">{{ $stats['total_properties'] ?? 0 }}</p>
                    <p class="label">Active Properties</p>
                </div>
                <div class="chip">
                    <p class="metric">{{ $stats['total_users'] ?? 0 }}</p>
                    <p class="label">Registered Users</p>
                </div>
                <div class="chip">
                    <p class="metric">{{ $stats['pending_refunds'] ?? 0 }}</p>
                    <p class="label">Pending Refunds</p>
                </div>
                <div class="chip">
                    <p class="metric">{{ $stats['open_tickets'] ?? 0 }}</p>
                    <p class="label">Open Tickets</p>
                </div>
            </div>
        </div>
    </div>
@endsection
