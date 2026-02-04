@extends('layouts.velzon.app')

@section('title', 'Revenue Report')
@section('page-title', 'Revenue Report')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Reports</p>
                <h1>Revenue Analysis</h1>
                <p class="lede">Track revenue by month, property, and payment method</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('admin.reports.revenue') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label class="label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->subMonths(6)->format('Y-m-d')) }}">
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
                    <a href="{{ route('admin.reports.revenue') }}" class="pill light" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Summary Stats -->
        <div class="metrics-grid">
            <div class="chip">
                <p class="metric">{{ number_format($summary['total_revenue'] ?? 0) }} KES</p>
                <p class="label">Total Revenue</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($summary['total_bookings'] ?? 0) }}</p>
                <p class="label">Total Bookings</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($summary['average_booking_value'] ?? 0) }} KES</p>
                <p class="label">Avg Booking Value</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($summary['commission_earned'] ?? 0) }} KES</p>
                <p class="label">Commission Earned</p>
            </div>
        </div>

        <!-- Revenue by Month -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Revenue by Month</h3>
                <p class="muted">Monthly revenue breakdown</p>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Bookings</th>
                            <th>Gross Revenue</th>
                            <th>Commission</th>
                            <th>Net Revenue</th>
                            <th>Growth</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyRevenue ?? [] as $month)
                            <tr>
                                <td><strong>{{ $month['month'] }}</strong></td>
                                <td>{{ $month['bookings'] }}</td>
                                <td>{{ number_format($month['gross_revenue']) }} KES</td>
                                <td style="color: #C62828;">{{ number_format($month['commission']) }} KES</td>
                                <td><strong>{{ number_format($month['net_revenue']) }} KES</strong></td>
                                <td>
                                    @if(isset($month['growth']))
                                        <span style="color: {{ $month['growth'] >= 0 ? '#2E7D32' : '#C62828' }};">
                                            {{ $month['growth'] >= 0 ? '↑' : '↓' }} {{ abs($month['growth']) }}%
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No revenue data for selected period</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($monthlyRevenue ?? []) > 0)
                        <tfoot style="background: #F5F5F5; font-weight: 700;">
                            <tr>
                                <td>TOTAL</td>
                                <td>{{ array_sum(array_column($monthlyRevenue, 'bookings')) }}</td>
                                <td>{{ number_format(array_sum(array_column($monthlyRevenue, 'gross_revenue'))) }} KES</td>
                                <td>{{ number_format(array_sum(array_column($monthlyRevenue, 'commission'))) }} KES</td>
                                <td>{{ number_format(array_sum(array_column($monthlyRevenue, 'net_revenue'))) }} KES</td>
                                <td>-</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Revenue by Property -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Revenue by Property</h3>
                <p class="muted">Top performing properties</p>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Location</th>
                            <th>Bookings</th>
                            <th>Total Revenue</th>
                            <th>Avg per Booking</th>
                            <th>Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propertyRevenue ?? [] as $property)
                            <tr>
                                <td><strong>{{ $property['name'] }}</strong></td>
                                <td>{{ $property['location'] }}</td>
                                <td>{{ $property['bookings'] }}</td>
                                <td><strong>{{ number_format($property['revenue']) }} KES</strong></td>
                                <td>{{ number_format($property['avg_booking']) }} KES</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="flex: 1; background: #E0E0E0; height: 8px; border-radius: 4px; overflow: hidden;">
                                            <div style="width: {{ $property['percentage'] }}%; background: var(--brand-primary, #652482); height: 100%;"></div>
                                        </div>
                                        <span style="font-size: 0.875rem; font-weight: 600;">{{ number_format($property['percentage'], 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No property revenue data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Revenue by Payment Method -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Revenue by Payment Method</h3>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                @foreach($paymentMethods ?? [] as $method)
                    <div style="background: #F5F5F5; padding: 1.5rem; border-radius: 8px;">
                        <p style="margin: 0; font-size: 0.875rem; color: #5a5661;">{{ $method['name'] }}</p>
                        <p style="margin: 0.5rem 0; font-size: 1.75rem; font-weight: 700; color: var(--brand-primary, #652482);">{{ number_format($method['revenue']) }} KES</p>
                        <p style="margin: 0; font-size: 0.875rem; color: #5a5661;">{{ $method['count'] }} transactions ({{ $method['percentage'] }}%)</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
