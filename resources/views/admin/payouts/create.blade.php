@extends('layouts.velzon.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('title', 'Create Payout')
@section('page-title', 'Create Payout')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Financial Management</p>
                <h1>Create Payout</h1>
                <p class="lede">Process a payment to property owner or staff</p>
            </div>
        </div>

        <form action="{{ route('admin.payouts.store') }}" method="POST">
            @csrf
            
            <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                <!-- Payout Details -->
                <div class="card">
                    <div class="card-head">
                        <h3>Payout Information</h3>
                    </div>
                    <div style="display: grid; gap: 1.25rem;">
                        <div>
                            <label class="label">Payee Type</label>
                            <select name="payee_type" class="form-control" required onchange="togglePayeeFields(this.value)">
                                <option value="">Select payee type...</option>
                                <option value="OWNER" {{ old('payee_type') == 'OWNER' ? 'selected' : '' }}>Property Owner</option>
                                <option value="STAFF" {{ old('payee_type') == 'STAFF' ? 'selected' : '' }}>Staff Member</option>
                                <option value="COMMISSION" {{ old('payee_type') == 'COMMISSION' ? 'selected' : '' }}>Commission</option>
                            </select>
                            @error('payee_type')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Payee</label>
                            <select name="payee_id" class="form-control" required>
                                <option value="">Select payee...</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ old('payee_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('payee_id')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="property-field" style="display: none;">
                            <label class="label">Property (Optional)</label>
                            <select name="property_id" class="form-control">
                                <option value="">Select property...</option>
                                @foreach($properties ?? [] as $property)
                                    <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                                @endforeach
                            </select>
                            @error('property_id')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="booking-field" style="display: none;">
                            <label class="label">Related Booking (Optional)</label>
                            <input type="number" name="booking_id" class="form-control" value="{{ old('booking_id') }}" placeholder="Enter booking ID...">
                            @error('booking_id')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label class="label">Gross Amount (KES)</label>
                                <input type="number" name="gross_amount" class="form-control" value="{{ old('gross_amount') }}" min="0" step="0.01" placeholder="0.00" required onkeyup="calculateNet()">
                                @error('gross_amount')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Commission (KES)</label>
                                <input type="number" name="commission_amount" class="form-control" value="{{ old('commission_amount', 0) }}" min="0" step="0.01" placeholder="0.00" onkeyup="calculateNet()">
                                @error('commission_amount')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label class="label">Deductions (KES)</label>
                                <input type="number" name="deductions" class="form-control" value="{{ old('deductions', 0) }}" min="0" step="0.01" placeholder="0.00" onkeyup="calculateNet()">
                                @error('deductions')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Net Amount (KES)</label>
                                <input type="number" name="net_amount" id="net_amount" class="form-control" value="{{ old('net_amount') }}" readonly style="background: #F5F5F5; font-weight: 700; color: var(--brand-primary, #652482);">
                                @error('net_amount')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Add any relevant notes...">{{ old('notes') }}</textarea>
                            @error('notes')
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
                            <h3>Payout Guidelines</h3>
                        </div>
                        <div style="display: grid; gap: 1rem; font-size: 0.875rem;">
                            <div style="background: #E3F2FD; padding: 1rem; border-radius: 6px;">
                                <p style="margin: 0; font-weight: 600; color: #1565C0;">Payee Types:</p>
                                <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem; color: #1565C0;">
                                    <li><strong>Owner:</strong> Property rental earnings</li>
                                    <li><strong>Staff:</strong> Salary or bonuses</li>
                                    <li><strong>Commission:</strong> Referral fees</li>
                                </ul>
                            </div>

                            <div style="background: #E8F5E9; padding: 1rem; border-radius: 6px;">
                                <p style="margin: 0; font-weight: 600; color: #2E7D32;">Calculation:</p>
                                <p style="margin: 0.5rem 0 0; color: #2E7D32; font-size: 0.8125rem;">
                                    <strong>Net Amount</strong> = Gross Amount - Commission - Deductions
                                </p>
                            </div>

                            <div style="background: #FFF3E0; padding: 1rem; border-radius: 6px;">
                                <p style="margin: 0; font-weight: 600; color: #E65100;">⚠️ Verify:</p>
                                <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem; color: #E65100;">
                                    <li>Payee bank details</li>
                                    <li>Amount calculations</li>
                                    <li>Related bookings</li>
                                    <li>Tax implications</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer;">Create Payout</button>
                <a href="{{ route('admin.payouts.index') }}" class="pill light" style="display: inline-flex; align-items: center; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        function togglePayeeFields(type) {
            const propertyField = document.getElementById('property-field');
            const bookingField = document.getElementById('booking-field');
            
            if (type === 'OWNER') {
                propertyField.style.display = 'block';
                bookingField.style.display = 'block';
            } else {
                propertyField.style.display = 'none';
                bookingField.style.display = 'none';
            }
        }

        function calculateNet() {
            const gross = parseFloat(document.querySelector('[name="gross_amount"]').value) || 0;
            const commission = parseFloat(document.querySelector('[name="commission_amount"]').value) || 0;
            const deductions = parseFloat(document.querySelector('[name="deductions"]').value) || 0;
            const net = gross - commission - deductions;
            document.getElementById('net_amount').value = net.toFixed(2);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const payeeType = document.querySelector('[name="payee_type"]').value;
            if (payeeType) {
                togglePayeeFields(payeeType);
            }
            calculateNet();
        });
    </script>
@endsection
