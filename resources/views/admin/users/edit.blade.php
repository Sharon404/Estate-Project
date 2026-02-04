@extends('layouts.velzon.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">User Management</p>
                <h1>Edit User</h1>
                <p class="lede">{{ $user->name }}</p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                <!-- User Information -->
                <div class="card">
                    <div class="card-head">
                        <h3>User Information</h3>
                    </div>
                    <div style="display: grid; gap: 1.25rem;">
                        <div>
                            <label class="label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Address</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Role</label>
                            <select name="role" class="form-control" required>
                                <option value="guest" {{ old('role', $user->role) == 'guest' ? 'selected' : '' }}>Guest</option>
                                <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                                <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- KYC Verification -->
                <div class="card">
                    <div class="card-head">
                        <h3>KYC Verification</h3>
                        <p class="muted" style="font-size: 0.875rem;">Update verification status</p>
                    </div>
                    <div style="display: grid; gap: 1.25rem;">
                        <div>
                            <label class="label">Status</label>
                            <select name="kyc_status" class="form-control">
                                <option value="PENDING" {{ old('kyc_status', $user->kyc_status) == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                <option value="VERIFIED" {{ old('kyc_status', $user->kyc_status) == 'VERIFIED' ? 'selected' : '' }}>Verified</option>
                                <option value="REJECTED" {{ old('kyc_status', $user->kyc_status) == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('kyc_status')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Verification Notes</label>
                            <textarea name="kyc_notes" class="form-control" rows="4" placeholder="Add notes about verification status...">{{ old('kyc_notes', $user->kyc_notes) }}</textarea>
                            @error('kyc_notes')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer;">Save Changes</button>
                <a href="{{ route('admin.users.show', $user) }}" class="pill light" style="display: inline-flex; align-items: center; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
@endsection
