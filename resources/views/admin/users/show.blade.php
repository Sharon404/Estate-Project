@extends('layouts.velzon.app')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">User Management</p>
                <h1>{{ $user->name }}</h1>
                <p class="lede">{{ $user->email }}</p>
            </div>
            <a href="{{ route('admin.users.edit', $user) }}" class="pill" style="cursor: pointer; text-decoration: none;">Edit User</a>
        </div>

        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <!-- User Information -->
            <div class="card">
                <div class="card-head">
                    <h3>User Information</h3>
                </div>
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Full Name</p>
                        <p style="margin: 0; font-weight: 600;">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Email</p>
                        <p style="margin: 0; font-weight: 600;">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Phone</p>
                        <p style="margin: 0; font-weight: 600;">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Address</p>
                        <p style="margin: 0;">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Role</p>
                        <span class="pill light">{{ strtoupper($user->role) }}</span>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Member Since</p>
                        <p style="margin: 0;">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- KYC Status -->
            <div class="card">
                <div class="card-head">
                    <h3>KYC Verification</h3>
                </div>
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <p class="label" style="margin: 0 0 0.5rem;">Status</p>
                        @if($user->kyc_status == 'VERIFIED')
                            <span style="background: #E8F5E9; color: #2E7D32; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; display: inline-block;">✓ VERIFIED</span>
                        @elseif($user->kyc_status == 'REJECTED')
                            <span style="background: #FFEBEE; color: #C62828; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; display: inline-block;">✗ REJECTED</span>
                        @else
                            <span style="background: #FFF3E0; color: #E65100; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; display: inline-block;">⏳ PENDING</span>
                        @endif
                    </div>
                    @if($user->kyc_verified_at)
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Verified At</p>
                            <p style="margin: 0;">{{ $user->kyc_verified_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif
                    @if($user->kyc_notes)
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Notes</p>
                            <p style="margin: 0; font-size: 0.875rem;">{{ $user->kyc_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Login History -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <div>
                    <h3>Recent Login Activity</h3>
                    <p class="muted">Last 20 login attempts</p>
                </div>
                <a href="{{ route('admin.users.login-history', $user) }}" class="link">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>IP Address</th>
                            <th>Device</th>
                            <th>Browser</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->loginHistory ?? [] as $login)
                            <tr>
                                <td>{{ $login->login_at->format('M d, Y H:i') }}</td>
                                <td><code style="font-size: 0.875rem;">{{ $login->ip_address }}</code></td>
                                <td>{{ $login->device_type ?? 'Unknown' }}</td>
                                <td>{{ $login->browser ?? 'Unknown' }}</td>
                                <td>
                                    @if($login->successful)
                                        <span style="color: #2E7D32;">✓ Success</span>
                                    @else
                                        <span style="color: #C62828;">✗ Failed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No login history yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
