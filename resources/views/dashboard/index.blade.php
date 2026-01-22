@extends('layouts.velzon.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <h4 class="text-muted fw-normal mb-0">Total Revenue</h4>
                            <h2 class="mb-2"><span class="counter-value" data-target="1856">0</span>k</h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="ri-shopping-cart-2-line text-info"></i>
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
                            <h4 class="text-muted fw-normal mb-0">Total Orders</h4>
                            <h2 class="mb-2"><span class="counter-value" data-target="1542">0</span></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="ri-stack-line text-success"></i>
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
                            <h4 class="text-muted fw-normal mb-0">Total Users</h4>
                            <h2 class="mb-2"><span class="counter-value" data-target="523">0</span></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="ri-user-line text-warning"></i>
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
                            <h2 class="mb-2"><span class="counter-value" data-target="87">0</span>%</h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-danger-subtle rounded fs-3">
                                <i class="ri-trending-up-line text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Sales Overview</h5>
                </div>
                <div class="card-body">
                    <div id="chart" style="height: 350px;">
                        <p class="text-center text-muted py-5">
                            <i class="ri-bar-chart-line"></i> Chart will appear here
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="activity-group">
                        <div class="activity">
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-success-subtle rounded-circle fs-13">
                                    <i class="ri-check-line"></i>
                                </span>
                            </div>
                            <h6 class="mb-1">New Order Created</h6>
                            <p class="text-muted text-sm mb-0">2 hours ago</p>
                        </div>

                        <div class="activity">
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-13">
                                    <i class="ri-user-add-line"></i>
                                </span>
                            </div>
                            <h6 class="mb-1">New User Registered</h6>
                            <p class="text-muted text-sm mb-0">4 hours ago</p>
                        </div>

                        <div class="activity">
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle rounded-circle fs-13">
                                    <i class="ri-download-cloud-line"></i>
                                </span>
                            </div>
                            <h6 class="mb-1">Report Generated</h6>
                            <p class="text-muted text-sm mb-0">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
