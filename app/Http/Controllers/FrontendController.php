<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
use App\Mail\ContactFormSubmitted;

class FrontendController extends Controller
{
    /**
     * Display the primary home page
     */
    public function index(): View
    {
        return view('frontend.home', [
            'title' => 'Home - GrandStay',
            'description' => 'Welcome to GrandStay, your premier destination for luxury hospitality.'
        ]);
    }

    /**
     * Display the about page
     */
    public function about(): View
    {
        return view('frontend.about', [
            'title' => 'About Us - GrandStay',
            'description' => 'Learn more about GrandStay and our commitment to excellence.'
        ]);
    }

    /**
     * Display the contact page
     */
    public function contact(): View
    {
        return view('frontend.contact', [
            'title' => 'Contact Us - GrandStay',
            'description' => 'Get in touch with our team. We\'d love to hear from you.'
        ]);
    }

    /**
     * Handle contact form submission
     */
    public function contactStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:5000',
        ]);

        // Store in database
        $contact = Contact::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'new',
        ]);

        // Send email notification to admin
        try {
            $adminEmail = env('ADMIN_EMAIL', 'admin@tausirental.com');
            Mail::to($adminEmail)->send(new ContactFormSubmitted($contact));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send contact form email: ' . $e->getMessage());
        }
        
        return redirect()->route('contact')->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    /**
     * Display properties (rooms) listing page
     */
    public function properties(): View
    {
        $properties = \App\Models\Property::with('images')
            ->where('status', 'APPROVED')
            ->get();
        
        return view('frontend.rooms', [
            'title' => 'Our Homes - Tausi Rental',
            'description' => 'Browse our collection of beautiful homes in Nanyuki.',
            'properties' => $properties,
        ]);
    }

    /**
     * Display facilities page
     */
    public function facilities(): View
    {
        return view('frontend.facilities', [
            'title' => 'Facilities - GrandStay',
            'description' => 'Discover our world-class amenities and facilities.'
        ]);
    }

    /**
     * Display gallery page
     */
    public function gallery(): View
    {
        return view('frontend.gallery', [
            'title' => 'Gallery - GrandStay',
            'description' => 'Explore stunning photos of our resort and facilities.'
        ]);
    }

    /**
     * Display testimonials page
     */
    public function testimonials(): View
    {
        return view('frontend.testimonials', [
            'title' => 'Testimonials - GrandStay',
            'description' => 'See what our guests say about their experience.'
        ]);
    }

    /**
     * Display blog listing page
     */
    public function blog(): View
    {
        return view('frontend.blog', [
            'title' => 'Blog - GrandStay',
            'description' => 'Read our latest travel tips, hotel updates, and guest stories.'
        ]);
    }

    /**
     * Display single property (room) page
     */
    public function propertySingle($id): View
    {
        $property = \App\Models\Property::findOrFail($id);
        
        return view('frontend.room-single', [
            'property' => $property,
            'title' => $property->name . ' - GrandStay',
            'description' => $property->description ?? 'Explore the details of this beautiful room.'
        ]);
    }

    /**
     * Display single blog post page
     */
    public function blogSingle($id): View
    {
        return view('frontend.blog-single', [
            'id' => $id,
            'title' => 'Blog Post - GrandStay',
            'description' => 'Read our latest blog post.'
        ]);
    }

}
