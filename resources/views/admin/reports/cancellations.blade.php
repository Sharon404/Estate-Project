@extends('layouts.velzon.app')

@section('title', 'Cancellation Report')
@section('page-title', 'Cancellation Report')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Reports</p>
                <h1>Cancellation Analysis</h1>
                <p class="lede">Track booking cancellations and trends</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('admin.reports.cancellations') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label class="label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->subMonths(3)->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="label">Property</label>
                    <select name="property_id" class="form-control">
                        <option value="">All Properties</option>
                        @foreach($properties ?? [] as $property)
                            <option value="{{ $property->id }}" {{ request('property_id') == $property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; cursor: pointer;">Generate</button>
                    <a href="{{ route('admin.reports.cancellations') }}" class="pill light" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Summary Stats -->
        <div class="metrics-grid">
            <div class="chip">
                <p class="metric">{{ $summary['total_cancellations'] ?? 0 }}</p>
                <p class="label">Total Cancellations</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($summary['cancellation_rate'] ?? 0, 1) }}%</p>
                <p class="label">Cancellation Rate</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($summary['revenue_lost'] ?? 0) }} KES</p>
                <p class="label">Revenue Lost</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($summary['avg_days_before'] ?? 0) }}</p>
                <p class="label">Avg Days Before</p>
            </div>
        </div>

        <!-- Cancellation Reasons -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Cancellation Reasons</h3>
                <p class="muted">Why are bookings being cancelled?</p>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Reason</th>
                            <th>Count</th>
                            <th>Percentage</th>
                            <th>Revenue Impact</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cancellationReasons ?? [] as $reason)
                            <tr>
                                <td><strong>{{ $reason['reason'] }}</strong></td>
                                <td>{{ $reason['count'] }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="flex: 1; background: #E0E0E0; height: 8px; border-radius: 4px; overflow: hidden; max-width: 150px;">
                                            <div style="width: {{ $reason['percentage'] }}%; background: var(--brand-primary, #652482); height: 100%;"></div>
                                        </div>
                                        <span style="font-weight: 600;">{{ number_format($reason['percentage'], 1) }}%</span>
                                    </div>
                                </td>
                                <td style="color: #C62828; font-weight: 600;">{{ number_format($reason['revenue_lost']) }} KES</td>
                                <td>
                                    @if($reason['trend'] == 'up')
                                        <span style="color: #C62828;">↑ Increasing</span>
                                    @elseif($reason['trend'] == 'down')
                                        <span style="color: #2E7D32;">↓ Decreasing</span>
                                    @else
                                        <span style="color: #5a5661;">→ Stable</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No cancellation data for selected period</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cancellation by Property -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Cancellations by Property</h3>
                <p class="muted">Which properties have highest cancellation rates</p>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Total Bookings</th>
                            <th>Cancellations</th>
                            <th>Cancellation Rate</th>
                            <th>Revenue Lost</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propertyCancellations ?? [] as $property)
                            <tr>
                                <td><strong>{{ $property['name'] }}</strong></td>
                                <td>{{ $property['total_bookings'] }}</td>
                                <td style="color: #C62828; font-weight: 600;">{{ $property['cancellations'] }}</td>
                                <td>
                                    <span style="font-weight: 700; color: {{ $property['rate'] >= 20 ? '#C62828' : ($property['rate'] >= 10 ? '#E65100' : '#2E7D32') }};">
                                        {{ number_format($property['rate'], 1) }}%
                                    </span>
                                </td>
                                <td>{{ number_format($property['revenue_lost']) }} KES</td>
                                <td>
                                    @if($property['rate'] >= 20)
                                        <span class="pill" style="background: #FFEBEE; color: #C62828;">⚠️ High Risk</span>
                                    @elseif($property['rate'] >= 10)
                                        <span class="pill" style="background: #FFF3E0; color: #E65100;">⚠ Moderate</span>
                                    @else
                                        <span class="pill" style="background: #E8F5E9; color: #2E7D32;">✓ Healthy</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No property cancellation data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
            <!-- Cancellation Timeline -->
            <div class="card">
                <div class="card-head">
                    <h3>When Guests Cancel</h3>
                    <p class="muted">Days before check-in</p>
                </div>
                <div style="display: grid; gap: 1rem;">
                    @foreach($cancellationTimeline ?? [] as $timeline)
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1;">
                                <p style="margin: 0; font-weight: 600;">{{ $timeline['range'] }}</p>
                                <div style="background: #E0E0E0; height: 8px; border-radius: 4px; overflow: hidden; margin-top: 0.5rem;">
                                    <div style="width: {{ $timeline['percentage'] }}%; background: var(--brand-primary, #652482); height: 100%;"></div>
                                </div>
                            </div>
                            <p style="margin: 0 0 0 1rem; font-weight: 700;">{{ $timeline['count'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Cancellations -->
            <div class="card">
                <div class="card-head">
                    <h3>Recent Cancellations</h3>
                    <p class="muted">Last 10 cancellations</p>
                </div>
                <div style="display: grid; gap: 1rem; max-height: 400px; overflow-y: auto;">
                    @forelse($recentCancellations ?? [] as $cancellation)
                        <div style="border-left: 4px solid #C62828; padding-left: 1rem;">
                            <p style="margin: 0; font-weight: 600;">{{ $cancellation['property'] }}</p>
                            <p style="margin: 0.25rem 0; font-size: 0.875rem; color: #5a5661;">
                                Booking #{{ $cancellation['booking_id'] }} • {{ $cancellation['guest'] }}
                            </p>
                            <p style="margin: 0.25rem 0 0; font-size: 0.75rem; color: #C62828;">
                                {{ $cancellation['cancelled_at'] }} • {{ $cancellation['reason'] }}
                            </p>
                        </div>
                    @empty
                        <p style="margin: 0; text-align: center; color: #5a5661; padding: 2rem;">No recent cancellations</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recommendations -->
        <div style="background: #E3F2FD; border-left: 4px solid #1565C0; padding: 1.5rem; margin-top: 1.5rem; border-radius: 4px;">
            <p style="margin: 0 0 0.5rem; font-weight: 600; color: #1565C0; font-size: 1.125rem;">💡 Recommendations</p>
            <ul style="margin: 0; padding-left: 1.5rem; color: #1565C0;">
                @foreach($recommendations ?? [] as $recommendation)
                    <li style="margin: 0.5rem 0;">{{ $recommendation }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
