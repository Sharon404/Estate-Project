<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     */
    public function index()
    {
        $properties = Property::with('images')->latest()->paginate(15);
        
        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        return view('admin.properties.create');
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nightly_rate' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string',
            'is_active' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        // Create property
        $property = Property::create([
            'name' => $validated['name'],
            'nightly_rate' => $validated['nightly_rate'],
            'currency' => $validated['currency'] ?? 'KES',
            'description' => $validated['description'] ?? null,
            'amenities' => $validated['amenities'] ?? null,
            'is_active' => $request->has('is_active'),
            'status' => 'APPROVED'
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                
                PropertyImage::create([
                    'property_id' => $property->id,
                    'file_path' => $path,
                    'is_primary' => $index === 0
                ]);
            }
        }

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property created successfully!');
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        $property->load('images');
        return view('admin.properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        $property->load('images');
        return view('admin.properties.edit', compact('property'));
    }

    /**
     * Update the specified property in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nightly_rate' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string',
            'is_active' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'delete_images' => 'nullable|array'
        ]);

        // Update property
        $property->update([
            'name' => $validated['name'],
            'nightly_rate' => $validated['nightly_rate'],
            'currency' => $validated['currency'],
            'description' => $validated['description'] ?? null,
            'amenities' => $validated['amenities'] ?? null,
            'is_active' => $request->has('is_active')
        ]);

        // Delete selected images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = PropertyImage::find($imageId);
                if ($image && $image->property_id === $property->id) {
                    Storage::disk('public')->delete($image->file_path);
                    $image->delete();
                }
            }
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                
                PropertyImage::create([
                    'property_id' => $property->id,
                    'file_path' => $path,
                    'is_primary' => $property->images()->count() === 0 && $index === 0
                ]);
            }
        }

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property updated successfully!');
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(Property $property)
    {
        // Delete all associated images
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->file_path);
            $image->delete();
        }

        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property deleted successfully!');
    }
}
