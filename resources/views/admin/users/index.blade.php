@extends('layouts.velzon.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">User Administration</p>
                <h1>Users</h1>
                <p class="lede">Manage user accounts, roles, and KYC verification</p>
            </div>
            <div class="pill">{{ $users->total() }} total</div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" class="filter-form">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; align-items: end;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Role</label>
                        <select name="role" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">KYC Status</label>
                        <select name="kyc_status" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">All Statuses</option>
                            <option value="PENDING" {{ request('kyc_status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                            <option value="VERIFIED" {{ request('kyc_status') == 'VERIFIED' ? 'selected' : '' }}>Verified</option>
                            <option value="REJECTED" {{ request('kyc_status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Search</label>
                        <input type="text" name="search" placeholder="Name, email, phone..." value="{{ request('search') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <button type="submit" style="padding: 0.5rem 1rem; background: #652482; color: white; border: none; border-radius: 4px; cursor: pointer;">Filter</button>
                    <a href="{{ route('admin.users.index') }}" style="padding: 0.5rem 1rem; background: #f0f0f0; color: #333; border: none; border-radius: 4px; cursor: pointer; text-align: center; text-decoration: none;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-head">
                <h3>All Users</h3>
                <p class="muted">System users and staff members</p>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>KYC Status</th>
                            <th>Joined</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td><span class="pill light">{{ strtoupper($user->role) }}</span></td>
                                <td>
                                    @if($user->kyc_status == 'VERIFIED')
                                        <span style="background: #E8F5E9; color: #2E7D32; padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.75rem;">✓ VERIFIED</span>
                                    @elseif($user->kyc_status == 'REJECTED')
                                        <span style="background: #FFEBEE; color: #C62828; padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.75rem;">✗ REJECTED</span>
                                    @else
                                        <span style="background: #FFF3E0; color: #E65100; padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.75rem;">⏳ PENDING</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a class="link" href="{{ route('admin.users.show', $user) }}">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 1rem;">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
