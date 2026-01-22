@extends('layouts.velzon.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="alert alert-info mb-4">
        <i class="ri-shield-admin-line"></i> You are viewing the <strong>Admin Dashboard</strong> with system statistics and user management options.
    </div>

    <!-- Admin Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <h4 class="text-muted fw-normal mb-0">Total Users</h4>
                            <h2 class="mb-2"><span class="counter-value" data-target="{{ $stats['total_users'] }}">0</span></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                <i class="ri-user-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <h4 class="text-muted fw-normal mb-0">Admin Users</h4>
                            <h2 class="mb-2"><span class="counter-value" data-target="{{ $stats['admin_users'] }}">0</span></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-danger-subtle rounded fs-3">
                                <i class="ri-shield-admin-line text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <h4 class="text-muted fw-normal mb-0">Staff Users</h4>
                            <h2 class="mb-2"><span class="counter-value" data-target="{{ $stats['staff_users'] }}">0</span></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="ri-team-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <h4 class="text-muted fw-normal mb-0">Growth Rate</h4>
                            <h2 class="mb-2"><span class="counter-value" data-target="{{ $stats['growth_rate'] }}">0</span>%</h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="ri-trending-up-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- System Overview -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">System Overview</h5>
                    <a href="#" class="btn btn-sm btn-primary">View Details</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Metric</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Revenue</td>
                                    <td><strong>${{ $stats['total_revenue'] }}k</strong></td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Total Orders</td>
                                    <td><strong>{{ $stats['total_orders'] }}</strong></td>
                                    <td><span class="badge bg-info">Processing</span></td>
                                </tr>
                                <tr>
                                    <td>System Health</td>
                                    <td><strong>100%</strong></td>
                                    <td><span class="badge bg-success">Operational</span></td>
                                </tr>
                                <tr>
                                    <td>Database Status</td>
                                    <td><strong>Connected</strong></td>
                                    <td><span class="badge bg-success">Online</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Admin Actions</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="ri-user-add-line"></i> Add User</h6>
                                <span class="badge bg-primary">New</span>
                            </div>
                            <p class="mb-1 text-muted">Create new user account</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="ri-settings-4-line"></i> System Settings</h6>
                            </div>
                            <p class="mb-1 text-muted">Configure system parameters</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="ri-file-chart-line"></i> Reports</h6>
                            </div>
                            <p class="mb-1 text-muted">Generate system reports</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="ri-database-2-line"></i> Backup</h6>
                            </div>
                            <p class="mb-1 text-muted">Manage database backups</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="ri-mail-line"></i> Email Templates</h6>
                            </div>
                            <p class="mb-1 text-muted">Manage email configurations</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Recent System Activities</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="activity-group">
                        <div class="activity d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="avatar-xs flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded-circle fs-13">
                                        <i class="ri-user-add-line"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-1">New User Created</h6>
                                    <p class="text-muted text-sm mb-0">Staff account "John Doe" was created</p>
                                </div>
                            </div>
                            <small class="text-muted">2 hours ago</small>
                        </div>

                        <div class="activity d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="avatar-xs flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-13">
                                        <i class="ri-shield-admin-line"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-1">Role Changed</h6>
                                    <p class="text-muted text-sm mb-0">User "Jane Smith" promoted to Admin</p>
                                </div>
                            </div>
                            <small class="text-muted">4 hours ago</small>
                        </div>

                        <div class="activity d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="avatar-xs flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle rounded-circle fs-13">
                                        <i class="ri-database-2-line"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-1">Database Backup</h6>
                                    <p class="text-muted text-sm mb-0">Automatic backup completed successfully</p>
                                </div>
                            </div>
                            <small class="text-muted">1 day ago</small>
                        </div>

                        <div class="activity d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="avatar-xs flex-shrink-0">
                                    <span class="avatar-title bg-danger-subtle rounded-circle fs-13">
                                        <i class="ri-alert-line"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-1">Failed Login Attempt</h6>
                                    <p class="text-muted text-sm mb-0">Invalid credentials from IP 192.168.1.100</p>
                                </div>
                            </div>
                            <small class="text-muted">2 days ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .activity {
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .activity:last-child {
            border-bottom: none;
        }

        .activity-group {
            margin: 0;
        }
    </style>

    <script>
        // Counter animation
        document.querySelectorAll('.counter-value').forEach(element => {
            const target = parseInt(element.dataset.target);
            let current = 0;
            const increment = target / 50;

            const counter = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(counter);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 30);
        });
    </script>
@endpush
