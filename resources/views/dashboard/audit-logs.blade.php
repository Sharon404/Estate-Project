@extends('layouts.velzon.app')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs & Compliance')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">System Monitoring</p>
                <h1>Audit Logs</h1>
                <p class="lede">Complete audit trail of all system events and user actions.</p>
            </div>
            <div class="pill">Append-only log</div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" class="filter-form">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; align-items: end;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Action</label>
                        <select name="action" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" @if(request('action') == $action) selected @endif>{{ $action }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Resource Type</label>
                        <select name="resource_type" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">All Resources</option>
                            @foreach($resourceTypes as $type)
                                <option value="{{ $type }}" @if(request('resource_type') == $type) selected @endif>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <button type="submit" style="padding: 0.5rem 1rem; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer;">Filter</button>
                    <a href="{{ route('admin.audit-logs') }}" style="padding: 0.5rem 1rem; background: #f0f0f0; color: #333; border: none; border-radius: 4px; cursor: pointer; text-align: center; text-decoration: none;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Audit Logs Table -->
        <div class="card">
            <div class="card-head">
                <h3>Event Log</h3>
                <p class="muted">All system events ({{ $logs->total() }} total)</p>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Action</th>
                            <th>Resource</th>
                            <th>Description</th>
                            <th>Performed By</th>
                            <th>IP Address</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <small>{{ $log->created_at->format('M d, Y H:i') }}</small>
                                </td>
                                <td>
                                    <span style="background: #E3F2FD; color: #1976D2; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: bold;">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $log->resource_type }}</small>
                                    @if($log->resource_id)
                                        <small style="color: #999;">#{{ $log->resource_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ \Illuminate\Support\Str::limit($log->description, 50) }}</small>
                                </td>
                                <td>
                                    @if($log->user)
                                        <small>{{ $log->user->name }}</small><br>
                                        <small style="color: #999; font-size: 0.75rem;">{{ $log->user_role }}</small>
                                    @else
                                        <small style="color: #999;">System</small>
                                    @endif
                                </td>
                                <td>
                                    <small style="font-family: monospace; color: #666;">{{ $log->ip_address ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($log->status === 'success')
                                        <span style="background: #C8E6C9; color: #2E7D32; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: bold;">Success</span>
                                    @elseif($log->status === 'failed')
                                        <span style="background: #FFCDD2; color: #C62828; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: bold;">Failed</span>
                                    @else
                                        <span style="background: #FFF9C4; color: #F57F17; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: bold;">{{ ucfirst($log->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.audit-log-detail', $log) }}" style="color: #2196F3; text-decoration: none; font-weight: bold;">View →</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 2rem; color: #999;">
                                    No audit logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
                <div style="padding: 1rem; display: flex; justify-content: center; gap: 0.5rem;">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .dash-shell { max-width: 1400px; margin: 0 auto; }
        .dash-head { margin-bottom: 2rem; }
        .dash-head h1 { font-size: 2rem; margin: 0.5rem 0; }
        .card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.5rem; }
        .card-head { margin-bottom: 1rem; }
        .card-head h3 { margin: 0 0 0.25rem 0; font-size: 1.1rem; }
        .card-head .muted { margin: 0; font-size: 0.875rem; color: #999; }
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; }
        .table thead { background: #f5f5f5; }
        .table th { padding: 0.75rem; text-align: left; font-weight: 600; font-size: 0.875rem; }
        .table td { padding: 0.75rem; border-bottom: 1px solid #e0e0e0; }
    </style>
@endsection
