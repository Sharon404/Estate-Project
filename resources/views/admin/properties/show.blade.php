@extends('layouts.velzon.app')

@section('title', 'Property Details')
@section('page-title', 'Property Details')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Property Management</p>
                <h1>{{ $property->name }}</h1>
                <p class="lede">{{ $property->location }}</p>
            </div>
            <a href="{{ route('admin.properties.edit', $property) }}" class="pill" style="background: var(--brand-primary, #652482); color: white; text-decoration: none; cursor: pointer;">Edit Property</a>
        </div>

        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <!-- Property Details -->
            <div>
                <!-- Photos -->
                @if(count($property->photos ?? []) > 0)
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div style="position: relative; width: 100%; height: 400px; overflow: hidden; border-radius: 8px;">
                        <img src="{{ $property->photos[0]->url ?? '/images/placeholder.jpg' }}" alt="{{ $property->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    @if(count($property->photos) > 1)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 0.5rem; margin-top: 0.5rem;">
                            @foreach($property->photos->skip(1)->take(5) as $photo)
                                <div style="position: relative; width: 100%; padding-bottom: 75%; overflow: hidden; border-radius: 4px; cursor: pointer;">
                                    <img src="{{ $photo->url }}" alt="Photo" style="position: absolute; width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif

                <div class="card">
                    <div class="card-head">
                        <h3>Property Information</h3>
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Description</p>
                            <p style="margin: 0; line-height: 1.6;">{{ $property->description ?? 'No description provided.' }}</p>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Property Type</p>
                                <p style="margin: 0; font-weight: 600;">{{ ucfirst($property->property_type ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Bedrooms</p>
                                <p style="margin: 0; font-weight: 600;">{{ $property->bedrooms ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Bathrooms</p>
                                <p style="margin: 0; font-weight: 600;">{{ $property->bathrooms ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Max Guests</p>
                                <p style="margin: 0; font-weight: 600;">{{ $property->max_guests ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Amenities</p>
                            @if($property->amenities)
                                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                                    @foreach(json_decode($property->amenities, true) ?? [] as $amenity)
                                        <span class="pill light" style="font-size: 0.875rem;">{{ $amenity }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p style="margin: 0; color: #5a5661;">No amenities listed</p>
                            @endif
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Location</p>
                            <p style="margin: 0;">{{ $property->location }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Pricing & Status -->
                <div class="card">
                    <div class="card-head">
                        <h3>Pricing & Status</h3>
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.5rem;">Price per Night</p>
                            <p style="margin: 0; font-size: 2rem; font-weight: 700; color: var(--brand-primary, #652482);">{{ number_format($property->price_per_night) }} KES</p>
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.5rem;">Status</p>
                            @if($property->status == 'ACTIVE')
                                <span class="pill" style="background: #E8F5E9; color: #2E7D32; padding: 0.75rem 1rem;">✓ Active</span>
                            @else
                                <span class="pill" style="background: #FFEBEE; color: #C62828; padding: 0.75rem 1rem;">✗ Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Owner Information -->
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-head">
                        <h3>Owner</h3>
                    </div>
                    <div style="display: grid; gap: 0.75rem;">
                        <div>
                            <p style="margin: 0; font-weight: 600; font-size: 1.125rem;">{{ $property->owner->name ?? 'N/A' }}</p>
                            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #5a5661;">{{ $property->owner->email ?? '' }}</p>
                        </div>
                        @if($property->owner && isset($property->owner->id))
                            <a href="{{ route('admin.users.show', $property->owner->id ?? 1) }}" class="link">View Owner Profile →</a>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-head">
                        <h3>Statistics</h3>
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Total Bookings</p>
                            <p style="margin: 0; font-size: 1.5rem; font-weight: 700;">{{ $property->bookings_count ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Total Revenue</p>
                            <p style="margin: 0; font-size: 1.5rem; font-weight: 700;">{{ number_format($property->total_revenue ?? 0) }} KES</p>
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Average Rating</p>
                            <p style="margin: 0; font-size: 1.5rem; font-weight: 700;">{{ number_format($property->average_rating ?? 0, 1) }} ⭐</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <div>
                    <h3>Recent Bookings</h3>
                    <p class="muted">Last 10 bookings for this property</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Guest</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($property->bookings ?? collect())->take(10) as $booking)
                            <tr>
                                <td><code>#{{ $booking->id }}</code></td>
                                <td>{{ $booking->guest->name ?? 'N/A' }}</td>
                                <td>{{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') : 'N/A' }}</td>
                                <td><strong>{{ number_format($booking->total_amount) }} KES</strong></td>
                                <td><span class="pill light">{{ $booking->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No bookings yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
