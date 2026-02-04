@extends('layouts.velzon.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('title', 'Create Refund')
@section('page-title', 'Create Refund')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Payment Management</p>
                <h1>Create Refund</h1>
                <p class="lede">Issue a refund for a booking</p>
            </div>
        </div>

        <form action="{{ route('admin.refunds.store') }}" method="POST">
            @csrf
            
            <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                <!-- Refund Details -->
                <div class="card">
                    <div class="card-head">
                        <h3>Refund Information</h3>
                    </div>
                    <div style="display: grid; gap: 1.25rem;">
                        <div>
                            <label class="label">Booking ID</label>
                            <input type="number" name="booking_id" class="form-control" value="{{ old('booking_id', request('booking_id')) }}" placeholder="Enter booking ID..." required>
                            @error('booking_id')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                            <p style="margin: 0.5rem 0 0; font-size: 0.875rem; color: #5a5661;">The booking must be confirmed or cancelled</p>
                        </div>

                        <div>
                            <label class="label">Refund Amount (KES)</label>
                            <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" min="1" step="0.01" placeholder="0.00" required>
                            @error('amount')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                            <p style="margin: 0.5rem 0 0; font-size: 0.875rem; color: #5a5661;">Cannot exceed the total booking amount</p>
                        </div>

                        <div>
                            <label class="label">Reason for Refund</label>
                            <textarea name="reason" class="form-control" rows="5" placeholder="Explain why this refund is being issued..." required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="auto_approve" value="1" {{ old('auto_approve') ? 'checked' : '' }}>
                                <span>Approve immediately (skip approval step)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Guidelines -->
                <div>
                    <div class="card">
                        <div class="card-head">
                            <h3>Refund Guidelines</h3>
                        </div>
                        <div style="display: grid; gap: 1rem; font-size: 0.875rem;">
                            <div style="background: #E3F2FD; padding: 1rem; border-radius: 6px;">
                                <p style="margin: 0; font-weight: 600; color: #1565C0;">Valid Reasons:</p>
                                <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem; color: #1565C0;">
                                    <li>Property unavailable</li>
                                    <li>Customer cancellation</li>
                                    <li>Payment error</li>
                                    <li>Service issues</li>
                                    <li>Duplicate payment</li>
                                </ul>
                            </div>

                            <div style="background: #FFF3E0; padding: 1rem; border-radius: 6px;">
                                <p style="margin: 0; font-weight: 600; color: #E65100;">⚠️ Important:</p>
                                <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem; color: #E65100;">
                                    <li>Verify booking details first</li>
                                    <li>Check payment status</li>
                                    <li>Document the reason clearly</li>
                                    <li>Notify the guest</li>
                                </ul>
                            </div>

                            <div style="background: #E8F5E9; padding: 1rem; border-radius: 6px;">
                                <p style="margin: 0; font-weight: 600; color: #2E7D32;">Processing Time:</p>
                                <p style="margin: 0.5rem 0 0; color: #2E7D32;">Refunds typically take 3-5 business days to reflect in the customer's account.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-top: 1.5rem;">
                        <div class="card-head">
                            <h3>Quick Actions</h3>
                        </div>
                        <a href="{{ route('admin.refunds.index') }}" class="link">← Back to Refunds List</a>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer;">Create Refund</button>
                <a href="{{ route('admin.refunds.index') }}" class="pill light" style="display: inline-flex; align-items: center; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
@endsection
