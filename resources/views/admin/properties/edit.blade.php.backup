@extends('layouts.velzon.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('title', 'Edit Property')
@section('page-title', 'Edit Property')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Property Management</p>
                <h1>Edit Property</h1>
                <p class="lede">{{ $property->name }}</p>
            </div>
        </div>

        <form action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-head">
                        <h3>Basic Information</h3>
                    </div>
                    <div style="display: grid; gap: 1.25rem;">
                        <div>
                            <label class="label">Property Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $property->name) }}" required>
                            @error('name')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label class="label">Property Type</label>
                                <select name="property_type" class="form-control" required>
                                    <option value="apartment" {{ old('property_type', $property->property_type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                    <option value="house" {{ old('property_type', $property->property_type) == 'house' ? 'selected' : '' }}>House</option>
                                    <option value="villa" {{ old('property_type', $property->property_type) == 'villa' ? 'selected' : '' }}>Villa</option>
                                    <option value="cottage" {{ old('property_type', $property->property_type) == 'cottage' ? 'selected' : '' }}>Cottage</option>
                                    <option value="studio" {{ old('property_type', $property->property_type) == 'studio' ? 'selected' : '' }}>Studio</option>
                                </select>
                                @error('property_type')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Location</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location', $property->location) }}" required>
                                @error('location')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                            <div>
                                <label class="label">Bedrooms</label>
                                <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $property->bedrooms) }}" min="0" required>
                                @error('bedrooms')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Bathrooms</label>
                                <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $property->bathrooms) }}" min="0" step="0.5" required>
                                @error('bathrooms')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Max Guests</label>
                                <input type="number" name="max_guests" class="form-control" value="{{ old('max_guests', $property->max_guests) }}" min="1" required>
                                @error('max_guests')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="label">Amenities (comma-separated)</label>
                            <input type="text" name="amenities" class="form-control" value="{{ old('amenities', is_array($property->amenities) ? implode(', ', $property->amenities) : $property->amenities) }}" placeholder="WiFi, Pool, Parking, AC">
                            @error('amenities')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing & Status -->
                <div>
                    <div class="card">
                        <div class="card-head">
                            <h3>Pricing & Status</h3>
                        </div>
                        <div style="display: grid; gap: 1.25rem;">
                            <div>
                                <label class="label">Price per Night (KES)</label>
                                <input type="number" name="price_per_night" class="form-control" value="{{ old('price_per_night', $property->price_per_night) }}" min="0" step="100" required>
                                @error('price_per_night')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="ACTIVE" {{ old('status', $property->status) == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                                    <option value="INACTIVE" {{ old('status', $property->status) == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Owner</label>
                                <select name="owner_id" class="form-control" required>
                                    @foreach($owners ?? [] as $owner)
                                        <option value="{{ $owner->id }}" {{ old('owner_id', $property->owner_id) == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                                    @endforeach
                                </select>
                                @error('owner_id')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Photo Management -->
                    <div class="card" style="margin-top: 1.5rem;">
                        <div class="card-head">
                            <h3>Photos</h3>
                            <p class="muted" style="font-size: 0.875rem;">Current: {{ count($property->photos ?? []) }} photos</p>
                        </div>
                        <div>
                            <label class="label">Upload New Photos</label>
                            <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                            <p style="margin: 0.5rem 0 0; font-size: 0.875rem; color: #5a5661;">Max 10 photos, 5MB each</p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer;">Save Changes</button>
                <a href="{{ route('admin.properties.show', $property) }}" class="pill light" style="display: inline-flex; align-items: center; text-decoration: none;">Cancel</a>
            </div>
        </form>

        <!-- Existing Photos -->
        @if(count($property->photos ?? []) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Manage Existing Photos</h3>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                @foreach($property->photos as $photo)
                    <div style="position: relative; border-radius: 8px; overflow: hidden;">
                        <img src="{{ $photo->url }}" alt="Photo" style="width: 100%; height: 150px; object-fit: cover;">
                        <div style="position: absolute; top: 0.5rem; right: 0.5rem; display: flex; gap: 0.5rem;">
                            @if($photo->is_primary)
                                <span class="pill" style="background: #2E7D32; color: white; font-size: 0.75rem;">Primary</span>
                            @else
                                <form action="{{ route('admin.properties.photos.primary', [$property, $photo]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="pill" style="background: white; color: #652482; font-size: 0.75rem; border: none; cursor: pointer;">Set Primary</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.properties.photos.delete', [$property, $photo]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="pill" style="background: #C62828; color: white; font-size: 0.75rem; border: none; cursor: pointer;" onclick="return confirm('Delete this photo?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection
