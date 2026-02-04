<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertiesController extends Controller
{
    public function index()
    {
        $properties = Property::withCount('bookings')->latest()->paginate(25);
        return view('admin.properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        $property->load(['images', 'bookings' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        return view('admin.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'status' => 'required|in:AVAILABLE,UNAVAILABLE,MAINTENANCE',
        ]);

        $property->update($validated);

        return redirect()->route('admin.properties.show', $property)
            ->with('success', 'Property updated successfully');
    }

    public function uploadPhotos(Request $request, Property $property)
    {
        $request->validate([
            'photos.*' => 'required|image|max:5120', // 5MB max
        ]);

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('properties', 'public');

            PropertyImage::create([
                'property_id' => $property->id,
                'image_path' => $path,
                'is_primary' => false,
            ]);
        }

        return back()->with('success', 'Photos uploaded successfully');
    }

    public function deletePhoto(Property $property, PropertyImage $image)
    {
        if ($image->property_id !== $property->id) {
            abort(404);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Photo deleted successfully');
    }

    public function setPrimaryPhoto(Property $property, PropertyImage $image)
    {
        if ($image->property_id !== $property->id) {
            abort(404);
        }

        // Unset all primary photos
        $property->images()->update(['is_primary' => false]);

        // Set new primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary photo updated');
    }
}
