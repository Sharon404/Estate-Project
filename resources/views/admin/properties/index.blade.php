@extends('layouts.velzon.app')

@section('title', 'Properties')
@section('page-title', 'Properties')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <p class="eyebrow">Property Management</p>
                    <h1>Properties</h1>
                    <p class="lede">Manage all property listings</p>
                </div>
                <a href="{{ route('admin.properties.create') }}" class="pill" style="background: var(--brand-primary, #652482); color: white; text-decoration: none;">
                    <i class="ri-add-line"></i> Add New Property
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('admin.properties.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label class="label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="ACTIVE" {{ request('status') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                        <option value="INACTIVE" {{ request('status') == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="label">Owner</label>
                    <input type="text" name="owner" class="form-control" placeholder="Search owner..." value="{{ request('owner') }}">
                </div>
                <div>
                    <label class="label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Property name..." value="{{ request('search') }}">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; cursor: pointer;">Filter</button>
                    <a href="{{ route('admin.properties.index') }}" class="pill light" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Properties Table -->
        <div class="card">
            <div class="card-head">
                <h3>All Properties ({{ $properties->total() }})</h3>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Owner</th>
                            <th>Location</th>
                            <th>Price/Night</th>
                            <th>Status</th>
                            <th>Photos</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($properties as $property)
                            <tr>
                                <td>
                                    <strong>{{ $property->name }}</strong>
                                </td>
                                <td>{{ $property->owner->name ?? 'N/A' }}</td>
                                <td>{{ $property->location }}</td>
                                <td><strong>{{ number_format($property->price_per_night) }} KES</strong></td>
                                <td>
                                    @if($property->status == 'ACTIVE')
                                        <span class="pill" style="background: #E8F5E9; color: #2E7D32;">Active</span>
                                    @else
                                        <span class="pill" style="background: #FFEBEE; color: #C62828;">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $property->photos_count ?? 0 }} photos</td>
                                <td>
                                    <a href="{{ route('admin.properties.show', $property) }}" class="link">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">No properties found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($properties->hasPages())
                <div class="card-footer">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
