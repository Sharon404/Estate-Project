@extends('layouts.velzon.app')

@section('title', 'Staff Dashboard')
@section('page-title', 'Staff Dashboard')

@section('content')
    <div class="alert alert-success mb-4">
        <i class="ri-team-line"></i> You are viewing the <strong>Staff Dashboard</strong> with your assigned tasks and orders.
    </div>

    <!-- Staff Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <h4 class="text-muted fw-normal mb-0">Assigned Tasks</h4>
                            <h2 class="mb-2"><span class="counter-value" data-target="{{ $stats['assigned_tasks'] }}">0</span></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="ri-task-2-line text-info"></i>
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
                            <h4 class="text-muted fw-normal mb-0">Completed</h4>
                            <h2 class="mb-2"><span class="counter-value" data-target="{{ $stats['completed_tasks'] }}">0</span></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="ri-checkbox-circle-line text-success"></i>
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
                            <h4 class="text-muted fw-normal mb-0">Pending Tasks</h4>
                            <h2 class="mb-2"><span class="counter-value" data-target="{{ $stats['pending_tasks'] }}">0</span></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="ri-time-line text-warning"></i>
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
                            <h2 class="mb-2"><span class="counter-value" data-target="{{ $stats['total_orders'] }}">0</span></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                <i class="ri-shopping-cart-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- My Tasks -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">My Pending Tasks</h5>
                    <a href="#" class="btn btn-sm btn-primary">Add Task</a>
                </div>
                <div class="card-body">
                    <div class="task-list">
                        <div class="task-item d-flex justify-content-between align-items-start p-3 border-bottom">
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="task1">
                                    <label class="form-check-label" for="task1">
                                        <strong>Complete property inspection report</strong>
                                        <p class="text-muted text-sm mb-0">Due: Jan 25, 2026</p>
                                    </label>
                                </div>
                            </div>
                            <span class="badge bg-warning">High</span>
                        </div>

                        <div class="task-item d-flex justify-content-between align-items-start p-3 border-bottom">
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="task2">
                                    <label class="form-check-label" for="task2">
                                        <strong>Follow up with clients</strong>
                                        <p class="text-muted text-sm mb-0">Due: Jan 23, 2026</p>
                                    </label>
                                </div>
                            </div>
                            <span class="badge bg-info">Medium</span>
                        </div>

                        <div class="task-item d-flex justify-content-between align-items-start p-3 border-bottom">
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="task3">
                                    <label class="form-check-label" for="task3">
                                        <strong>Update property listings</strong>
                                        <p class="text-muted text-sm mb-0">Due: Jan 22, 2026</p>
                                    </label>
                                </div>
                            </div>
                            <span class="badge bg-warning">High</span>
                        </div>

                        <div class="task-item d-flex justify-content-between align-items-start p-3">
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="task4">
                                    <label class="form-check-label" for="task4">
                                        <strong>Schedule appointments</strong>
                                        <p class="text-muted text-sm mb-0">Due: Jan 24, 2026</p>
                                    </label>
                                </div>
                            </div>
                            <span class="badge bg-success">Low</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Recent Orders</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="orders-list">
                        <div class="order-item d-flex justify-content-between align-items-center p-3 border-bottom">
                            <div>
                                <h6 class="mb-1">Order #2024-001</h6>
                                <p class="text-muted text-sm mb-0">Property Listing Services</p>
                            </div>
                            <span class="badge bg-success">Completed</span>
                        </div>

                        <div class="order-item d-flex justify-content-between align-items-center p-3 border-bottom">
                            <div>
                                <h6 class="mb-1">Order #2024-002</h6>
                                <p class="text-muted text-sm mb-0">Consultation Services</p>
                            </div>
                            <span class="badge bg-info">In Progress</span>
                        </div>

                        <div class="order-item d-flex justify-content-between align-items-center p-3 border-bottom">
                            <div>
                                <h6 class="mb-1">Order #2024-003</h6>
                                <p class="text-muted text-sm mb-0">Property Inspection</p>
                            </div>
                            <span class="badge bg-warning">Pending</span>
                        </div>

                        <div class="order-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h6 class="mb-1">Order #2024-004</h6>
                                <p class="text-muted text-sm mb-0">Valuation Report</p>
                            </div>
                            <span class="badge bg-secondary">On Hold</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance & Statistics -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Your Performance</h5>
                </div>
                <div class="card-body">
                    <div class="performance-stats">
                        <div class="stat-row d-flex justify-content-between align-items-center mb-3">
                            <span>Task Completion Rate</span>
                            <div class="stat-bar" style="width: 200px;">
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" style="width: 67%;">67%</div>
                                </div>
                            </div>
                        </div>

                        <div class="stat-row d-flex justify-content-between align-items-center mb-3">
                            <span>On-Time Delivery</span>
                            <div class="stat-bar" style="width: 200px;">
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" style="width: 85%;">85%</div>
                                </div>
                            </div>
                        </div>

                        <div class="stat-row d-flex justify-content-between align-items-center">
                            <span>Client Satisfaction</span>
                            <div class="stat-bar" style="width: 200px;">
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-warning" style="width: 92%;">92%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="ri-task-add-line"></i> Create New Task
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="ri-calendar-line"></i> View Schedule
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="ri-file-chart-line"></i> View Reports
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="ri-message-line"></i> Messages
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="ri-file-text-line"></i> Documentation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .task-item {
            transition: background-color 0.3s ease;
        }

        .task-item:hover {
            background-color: #f9f9f9;
        }

        .order-item {
            transition: background-color 0.3s ease;
        }

        .order-item:hover {
            background-color: #f9f9f9;
        }

        .progress {
            background-color: #e9ecef;
        }

        .progress-bar {
            background-color: #50a5f1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .list-group-item {
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: #323238;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .list-group-item:hover {
            background-color: #f1f5f7;
            color: var(--bs-primary);
        }

        .list-group-item i {
            font-size: 1.1rem;
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

        // Checkbox handling
        document.querySelectorAll('.form-check-input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.closest('.form-check-label').style.textDecoration = 'line-through';
                    this.closest('.form-check-label').style.opacity = '0.6';
                } else {
                    this.closest('.form-check-label').style.textDecoration = 'none';
                    this.closest('.form-check-label').style.opacity = '1';
                }
            });
        });
    </script>
@endpush
