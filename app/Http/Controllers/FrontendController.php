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
            'title' => 'Tausi Holiday & Getaway Homes',
            'description' => 'AN ENTIRE HOUSE JUST FOR YOU · KES 25,000 PER NIGHT · BREAKFAST INCLUDED'
        ]);
    }

    /**
     * Display the alternative home page layout
     */
    public function home2(): View
    {
        return redirect()->route('home');
    }

    /**
     * Display the about page
     */
    public function about(): View
    {
        return redirect()->to(route('home').'#about');
    }

    /**
     * Display the contact page
     */
    public function contact(): View
    {
        return redirect()->to(route('home').'#contact');
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
        
        return redirect()->to(route('home').'#contact')->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    /**
     * Display properties (rooms) listing page
     */
    public function properties(): View
    {
        return redirect()->to(route('home').'#services');
    }

    /**
     * Display facilities page
     */
    public function facilities(): View
    {
        return redirect()->to(route('home').'#services');
    }

    /**
     * Display offers page
     */
    public function offers(): View
    {
        return redirect()->to(route('home').'#pricing');
    }

    /**
     * Display gallery page
     */
    public function gallery(): View
    {
        return redirect()->to(route('home'));
    }

    /**
     * Display testimonials page
     */
    public function testimonials(): View
    {
        return redirect()->to(route('home').'#testimonial');
    }

    /**
     * Display blog listing page
     */
    public function blog(): View
    {
        return redirect()->to(route('home'));
    }

    /**
     * Display single property (room) page
     */
    public function propertySingle($id): View
    {
        return redirect()->to(route('home').'#pricing');
    }

    /**
     * Display single offer page
     */
    public function offerSingle($id): View
    {
        return redirect()->to(route('home').'#pricing');
    }

    /**
     * Display single blog post page
     */
    public function blogSingle($id): View
    {
        return redirect()->to(route('home'));
    }

    /**
     * Display the reservation form page
     */
    public function reservation(): View
    {
        return redirect()->to(route('home').'#booking');
    }

}
