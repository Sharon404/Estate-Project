@extends('layouts.velzon.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('title', 'Edit Property')
@section('page-title', 'Edit Property')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="eyebrow">Property Management</p>
                    <h1>Edit Property</h1>
                    <p class="lede">{{ $property->name }}</p>
                </div>
                <a href="{{ route('admin.properties.index') }}" class="pill light">
                    <i class="ri-arrow-left-line"></i> Back to Properties
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
                            <label class="label">Property Name <span style="color: #C62828;">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $property->name) }}" required>
                            @error('name')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $property->description) }}</textarea>
                            <small class="text-muted">A detailed description will help guests understand what to expect</small>
                            @error('description')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                            <div>
                                <label class="label">Nightly Rate <span style="color: #C62828;">*</span></label>
                                <input type="number" name="nightly_rate" class="form-control" value="{{ old('nightly_rate', $property->nightly_rate) }}" min="0" step="0.01" required>
                                @error('nightly_rate')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Currency</label>
                                <select name="currency" class="form-control">
                                    <option value="KES" {{ old('currency', $property->currency) == 'KES' ? 'selected' : '' }}>KES</option>
                                    <option value="USD" {{ old('currency', $property->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR" {{ old('currency', $property->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="GBP" {{ old('currency', $property->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                </select>
                                @error('currency')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="label">Amenities</label>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin-bottom: 0.5rem;">
                                @php
                                    $commonAmenities = [
                                        'Smart TV', 'Free Wi-Fi', 'Air Conditioning', 'Fully Equipped Kitchen',
                                        'Private Bathroom', 'Comfortable Bedding', 'Coffee Machine', 'Hairdryer',
                                        'Iron & Ironing Board', 'Safe Box', 'Parking', 'Swimming Pool',
                                        'Garden', 'BBQ Grill', 'Washing Machine', 'Microwave'
                                    ];
                                    $selectedAmenities = old('amenities', $property->amenities ?? []);
                                @endphp
                                @foreach($commonAmenities as $amenity)
                                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                                        <input type="checkbox" name="amenities[]" value="{{ $amenity }}" 
                                               {{ in_array($amenity, is_array($selectedAmenities) ? $selectedAmenities : []) ? 'checked' : '' }}>
                                        <span>{{ $amenity }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('amenities')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Images Management -->
                <div>
                    <div class="card" style="margin-bottom: 1.5rem;">
                        <div class="card-head">
                            <h3>Property Images</h3>
                        </div>
                        <div style="display: grid; gap: 1rem;">
                            <!-- Existing Images -->
                            @if($property->images->count() > 0)
                                <div>
                                    <p style="font-weight: 500; margin-bottom: 0.75rem;">Current Images ({{ $property->images->count() }})</p>
                                    <div style="display: grid; gap: 0.5rem; max-height: 300px; overflow-y: auto;">
                                        @foreach($property->images as $image)
                                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f5f5f5; border-radius: 4px;">
                                                <img src="{{ $image->url }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                <div style="flex: 1; min-width: 0;">
                                                    <div style="font-size: 0.875rem; word-break: break-all; color: #666;">{{ basename($image->file_path) }}</div>
                                                    @if($image->is_primary)
                                                        <span class="pill" style="background: #E8F5E9; color: #2E7D32; font-size: 0.75rem; display: inline-block; margin-top: 0.25rem;">Primary</span>
                                                    @endif
                                                </div>
                                                <label style="display: flex; align-items: center; cursor: pointer;">
                                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" style="margin: 0;">
                                                    <i class="ri-delete-bin-line" style="color: #C62828; margin-left: 0.5rem;"></i>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted" style="display: block; margin-top: 0.5rem;">Check the checkbox to delete images</small>
                                </div>
                            @else
                                <p style="color: #999; font-size: 0.875rem;">No images uploaded yet</p>
                            @endif

                            <!-- Upload New Images -->
                            <div>
                                <label class="label">Add More Images</label>
                                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                                <small class="text-muted">You can upload multiple images. Max 5MB per image.</small>
                                @error('images')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                                @error('images.*')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="card" style="margin-bottom: 1.5rem;">
                        <div class="card-head">
                            <h3>Status</h3>
                        </div>
                        <div style="display: grid; gap: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $property->is_active) ? 'checked' : '' }}>
                                <span>Active (Visible to guests)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card">
                        <div style="display: grid; gap: 0.75rem;">
                            <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; cursor: pointer; width: 100%; padding: 0.75rem;">
                                <i class="ri-save-line"></i> Update Property
                            </button>
                            <a href="{{ route('admin.properties.index') }}" class="pill light" style="text-align: center; text-decoration: none; padding: 0.75rem;">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
