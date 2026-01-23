<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

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
     * Display the alternative home page layout
     */
    public function home2(): View
    {
        return view('frontend.homepage-2', [
            'title' => 'Home 2 - GrandStay',
            'description' => 'Experience luxury at GrandStay with our alternative homepage design.'
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
     * Display properties (rooms) listing page
     */
    public function properties(): View
    {
        return view('frontend.rooms', [
            'title' => 'Our Rooms - GrandStay',
            'description' => 'Browse our exquisite collection of luxury rooms and suites.'
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
     * Display offers page
     */
    public function offers(): View
    {
        return view('frontend.offers', [
            'title' => 'Special Offers - GrandStay',
            'description' => 'Check out our amazing special offers and packages.'
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
        return view('frontend.room-single', [
            'id' => $id,
            'title' => 'Room Details - GrandStay',
            'description' => 'Explore the details of this beautiful room.'
        ]);
    }

    /**
     * Display single offer page
     */
    public function offerSingle($id): View
    {
        return view('frontend.offer-single', [
            'id' => $id,
            'title' => 'Special Offer - GrandStay',
            'description' => 'Learn more about this amazing offer.'
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

    /**
     * Display the reservation form page
     */
    public function reservation(): View
    {
        return view('frontend.reservation', [
            'title' => 'Make a Reservation - GrandStay',
            'description' => 'Book your perfect stay with us.'
        ]);
    }

}