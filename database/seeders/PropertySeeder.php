<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            'Smart TV',
            'Free Wi-Fi',
            'Air Conditioning',
            'Fully Equipped Kitchen',
            'Private Bathroom',
            'Comfortable Bedding',
            'Coffee Machine',
            'Hairdryer',
            'Iron & Ironing Board',
            'Safe Box'
        ];

        $descriptions = [
            'Tausi Holiday Home' => 'Enjoy a luxurious stay at our flagship property featuring spacious rooms, modern amenities, and warm hospitality. Perfect for families and small groups seeking comfort and relaxation in beautiful Nanyuki.',
            'Deluxe Room' => 'Experience elegance and comfort in our deluxe room, furnished with premium bedding and modern conveniences for a memorable stay.',
            'Superior Room' => 'Discover superior comfort with our well-appointed room featuring quality furnishings and all essential amenities for your relaxation.',
            'Executive Room' => 'Treat yourself to our executive room offering premium comfort, workspace, and modern facilities for the discerning traveler.',
            'Premium Room' => 'Indulge in our premium room with luxury furnishings, enhanced amenities, and personalized service for an unforgettable experience.',
            'Family Room' => 'Perfect for families, our spacious family room provides ample space and all the comfort you need for a wonderful vacation.',
            'Luxury Suite' => 'Experience ultimate luxury in our spacious suite featuring upscale furnishings, premium bedding, and exclusive amenities.',
            'Premier Room' => 'Our premier room combines style and comfort with top-tier amenities and exceptional service for a premium experience.'
        ];

        foreach (Property::all() as $prop) {
            $prop->update([
                'description' => $descriptions[$prop->name] ?? 'A beautiful property perfect for your stay.',
                'amenities' => $amenities
            ]);
        }
    }
}
