@extends('layouts.velzon.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('title', 'Add New Property')
@section('page-title', 'Add New Property')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="eyebrow">Property Management</p>
                    <h1>Add New Property</h1>
                    <p class="lede">Create a new rental property listing</p>
                </div>
                <a href="{{ route('admin.properties.index') }}" class="pill light">
                    <i class="ri-arrow-left-line"></i> Back to Properties
                </a>
            </div>
        </div>

        <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-head">
                        <h3>Basic Information</h3>
                    </div>
                    <div style="display: grid; gap: 1.25rem;">
                        <div>
                            <label class="label">Property Name <span style="color: #C62828;">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g., 3 Bedroom Villa - Nanyuki">
                            @error('name')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Describe the property, its features, and what makes it special...">{{ old('description') }}</textarea>
                            <small class="text-muted">A detailed description will help guests understand what to expect</small>
                            @error('description')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                            <div>
                                <label class="label">Nightly Rate <span style="color: #C62828;">*</span></label>
                                <input type="number" name="nightly_rate" class="form-control" value="{{ old('nightly_rate', 25000) }}" min="0" step="0.01" required>
                                @error('nightly_rate')
                                    <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Currency</label>
                                <select name="currency" class="form-control">
                                    <option value="KES" {{ old('currency') == 'KES' ? 'selected' : '' }}>KES</option>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
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
                                @endphp
                                @foreach($commonAmenities as $amenity)
                                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                                        <input type="checkbox" name="amenities[]" value="{{ $amenity }}" 
                                               {{ in_array($amenity, old('amenities', [])) ? 'checked' : '' }}>
                                        <span>{{ $amenity }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('amenities')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Property Images</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">You can upload multiple images. First image will be the main image. Max 5MB per image.</small>
                            @error('images')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                            @error('images.*')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status & Actions -->
                <div>
                    <div class="card" style="margin-bottom: 1.5rem;">
                        <div class="card-head">
                            <h3>Status</h3>
                        </div>
                        <div style="display: grid; gap: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span>Active (Visible to guests)</span>
                            </label>
                        </div>
                    </div>

                    <div class="card">
                        <div style="display: grid; gap: 0.75rem;">
                            <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; cursor: pointer; width: 100%;">
                                <i class="ri-save-line"></i> Create Property
                            </button>
                            <a href="{{ route('admin.properties.index') }}" class="pill light" style="text-align: center; text-decoration: none;">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
