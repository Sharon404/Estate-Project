@extends('layouts.velzon.app')

@section('title', 'Login History')
@section('page-title', 'Login History')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">User Management</p>
                <h1>Login History</h1>
                <p class="lede">{{ $user->name }}</p>
            </div>
            <a href="{{ route('admin.users.show', $user) }}" class="pill light" style="text-decoration: none;">← Back to User</a>
        </div>

        <!-- Stats -->
        <div class="metrics-grid">
            <div class="chip">
                <p class="metric">{{ $stats['total_logins'] ?? 0 }}</p>
                <p class="label">Total Logins</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['successful'] ?? 0 }}</p>
                <p class="label">Successful</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['failed'] ?? 0 }}</p>
                <p class="label">Failed Attempts</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['unique_ips'] ?? 0 }}</p>
                <p class="label">Unique IPs</p>
            </div>
        </div>

        <!-- Login History Table -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Login Activity ({{ $loginHistory->total() }})</h3>
                <p class="muted">Complete login history for this user</p>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>IP Address</th>
                            <th>Device Type</th>
                            <th>Browser</th>
                            <th>Operating System</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loginHistory as $login)
                            <tr style="background: {{ !$login->successful ? '#FFF5F5' : '' }};">
                                <td>
                                    <strong>{{ $login->login_at->format('M d, Y') }}</strong><br>
                                    <span style="font-size: 0.875rem; color: #5a5661;">{{ $login->login_at->format('H:i:s') }}</span>
                                </td>
                                <td>
                                    <code style="font-size: 0.875rem; background: #F5F5F5; padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $login->ip_address }}</code>
                                </td>
                                <td>{{ $login->device_type ?? 'Unknown' }}</td>
                                <td>{{ $login->browser ?? 'Unknown' }}</td>
                                <td>{{ $login->os ?? 'Unknown' }}</td>
                                <td>{{ $login->location ?? 'Unknown' }}</td>
                                <td>
                                    @if($login->successful)
                                        <span style="color: #2E7D32; font-weight: 600;">✓ Success</span>
                                    @else
                                        <span style="color: #C62828; font-weight: 600;">✗ Failed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">No login history recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($loginHistory->hasPages())
                <div class="card-footer">
                    {{ $loginHistory->links() }}
                </div>
            @endif
        </div>

        <!-- Security Alerts -->
        @if(count($suspiciousActivity ?? []) > 0)
        <div style="background: #FFF3E0; border-left: 4px solid #E65100; padding: 1rem; margin-top: 1.5rem; border-radius: 4px;">
            <p style="margin: 0; font-weight: 600; color: #E65100;">⚠️ Security Alerts</p>
            <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem; color: #5a5661;">
                @foreach($suspiciousActivity as $alert)
                    <li>{{ $alert }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
@endsection
