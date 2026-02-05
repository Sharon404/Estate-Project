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
                            <th>Image</th>
                            <th>Name</th>
                            <th>Nightly Rate</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($properties as $property)
                            <tr>
                                <td>
                                    @if($property->images->count() > 0)
                                        <img src="{{ $property->images->first()->url }}" alt="{{ $property->name }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 40px; border-radius: 4px;">
                                            <i class="ri-image-line text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $property->name }}</strong>
                                    @if($property->description)
                                        <br>
                                        <small class="text-muted">{{ Str::limit($property->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $property->currency }} {{ number_format($property->nightly_rate, 2) }}</strong>
                                    <br>
                                    <small class="text-muted">per night</small>
                                </td>
                                <td>
                                    @if($property->is_active)
                                        <span class="pill" style="background: #E8F5E9; color: #2E7D32;">Active</span>
                                    @else
                                        <span class="pill" style="background: #FFEBEE; color: #C62828;">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $property->created_at ? $property->created_at->format('M d, Y') : 'N/A' }}</small>
                                </td>
                                <td style="text-align: right;">
                                    <div class="btn-group" role="group" style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('admin.properties.edit', $property) }}" class="pill light" style="text-decoration: none; font-size: 0.875rem;">
                                            <i class="ri-edit-line"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this property?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="pill light" style="background: #FFEBEE; color: #C62828; border: none; cursor: pointer; font-size: 0.875rem;">
                                                <i class="ri-delete-bin-line"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-3">No properties found</p>
                                    <a href="{{ route('admin.properties.create') }}" class="pill" style="background: var(--brand-primary, #652482); color: white; text-decoration: none;">
                                        <i class="ri-add-line"></i> Add Your First Property
                                    </a>
                                </td>
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
