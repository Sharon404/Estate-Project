@extends('layouts.velzon.app')

@section('title', 'Occupancy Report')
@section('page-title', 'Occupancy Report')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Reports</p>
                <h1>Occupancy Rates</h1>
                <p class="lede">Property occupancy and availability analysis</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('admin.reports.occupancy') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label class="label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
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
                    <a href="{{ route('admin.reports.occupancy') }}" class="pill light" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Summary Stats -->
        <div class="metrics-grid">
            <div class="chip">
                <p class="metric">{{ number_format($summary['overall_occupancy'] ?? 0, 1) }}%</p>
                <p class="label">Overall Occupancy</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($summary['occupied_nights'] ?? 0) }}</p>
                <p class="label">Occupied Nights</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($summary['available_nights'] ?? 0) }}</p>
                <p class="label">Available Nights</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($summary['total_nights'] ?? 0) }}</p>
                <p class="label">Total Nights</p>
            </div>
        </div>

        <!-- Occupancy by Property -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Property Occupancy Rates</h3>
                <p class="muted">{{ request('start_date', now()->startOfMonth()->format('M d, Y')) }} - {{ request('end_date', now()->endOfMonth()->format('M d, Y')) }}</p>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Location</th>
                            <th>Total Nights</th>
                            <th>Occupied Nights</th>
                            <th>Available Nights</th>
                            <th>Occupancy Rate</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($occupancyData ?? [] as $data)
                            <tr>
                                <td><strong>{{ $data['property_name'] }}</strong></td>
                                <td>{{ $data['location'] }}</td>
                                <td>{{ $data['total_nights'] }}</td>
                                <td style="color: #2E7D32; font-weight: 600;">{{ $data['occupied_nights'] }}</td>
                                <td>{{ $data['available_nights'] }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="flex: 1; background: #E0E0E0; height: 8px; border-radius: 4px; overflow: hidden; max-width: 100px;">
                                            <div style="width: {{ $data['occupancy_rate'] }}%; background: {{ $data['occupancy_rate'] >= 70 ? '#2E7D32' : ($data['occupancy_rate'] >= 40 ? '#E65100' : '#C62828') }}; height: 100%;"></div>
                                        </div>
                                        <span style="font-weight: 700; color: {{ $data['occupancy_rate'] >= 70 ? '#2E7D32' : ($data['occupancy_rate'] >= 40 ? '#E65100' : '#C62828') }};">{{ number_format($data['occupancy_rate'], 1) }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if($data['occupancy_rate'] >= 70)
                                        <span class="pill" style="background: #E8F5E9; color: #2E7D32;">Excellent</span>
                                    @elseif($data['occupancy_rate'] >= 50)
                                        <span class="pill" style="background: #E3F2FD; color: #1565C0;">Good</span>
                                    @elseif($data['occupancy_rate'] >= 30)
                                        <span class="pill" style="background: #FFF3E0; color: #E65100;">Fair</span>
                                    @else
                                        <span class="pill" style="background: #FFEBEE; color: #C62828;">Poor</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">No occupancy data for selected period</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Occupancy Trends -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Occupancy by Day of Week</h3>
                <p class="muted">Which days have highest bookings</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1rem; padding: 1rem;">
                @foreach($dayOfWeekOccupancy ?? [] as $day)
                    <div style="text-align: center;">
                        <div style="height: 150px; display: flex; align-items: flex-end; justify-content: center;">
                            <div style="width: 40px; background: var(--brand-primary, #652482); border-radius: 4px 4px 0 0; height: {{ $day['percentage'] }}%;"></div>
                        </div>
                        <p style="margin: 0.5rem 0; font-weight: 700;">{{ $day['percentage'] }}%</p>
                        <p style="margin: 0; font-size: 0.875rem; color: #5a5661;">{{ $day['day'] }}</p>
                        <p style="margin: 0; font-size: 0.75rem; color: #5a5661;">{{ $day['bookings'] }} bookings</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
            <!-- Peak Season -->
            <div class="card">
                <div class="card-head">
                    <h3>Peak Periods</h3>
                    <p class="muted">Highest occupancy periods</p>
                </div>
                <div style="display: grid; gap: 1rem;">
                    @foreach($peakPeriods ?? [] as $period)
                        <div style="border-left: 4px solid #2E7D32; padding-left: 1rem;">
                            <p style="margin: 0; font-weight: 600;">{{ $period['period'] }}</p>
                            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #5a5661;">{{ $period['occupancy'] }}% occupancy • {{ $period['bookings'] }} bookings</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Low Season -->
            <div class="card">
                <div class="card-head">
                    <h3>Low Periods</h3>
                    <p class="muted">Improvement opportunities</p>
                </div>
                <div style="display: grid; gap: 1rem;">
                    @foreach($lowPeriods ?? [] as $period)
                        <div style="border-left: 4px solid #C62828; padding-left: 1rem;">
                            <p style="margin: 0; font-weight: 600;">{{ $period['period'] }}</p>
                            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #5a5661;">{{ $period['occupancy'] }}% occupancy • {{ $period['bookings'] }} bookings</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
