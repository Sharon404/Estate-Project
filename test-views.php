<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Mock model class for route binding
class TestModel {
    public function __construct(public $id, public $attributes = []) {}
    
    public function __get($key) {
        return $this->attributes[$key] ?? null;
    }
    
    public function getRouteKey() {
        return $this->id;
    }
    
    public function __toString() {
        return (string)$this->id;
    }
}

// Test View Rendering
echo "=== TESTING ALL VIEW TEMPLATES ===\n\n";

$viewTests = [
    // Admin User Views
    'admin/users/index' => ['users' => collect([]), 'stats' => []],
    'admin/users/show' => ['user' => (object)[
        'id' => 1, 'name' => 'Test User', 'email' => 'test@test.com', 'role' => 'admin',
        'phone' => '0700000000', 'address' => 'Test Address', 'kyc_status' => 'PENDING',
        'created_at' => now(), 'loginHistory' => collect([]),
        '__route_key' => 'id', '__route_value' => 1  // For route() helper
    ]],
    'admin/users/edit' => ['user' => (object)[
        'id' => 1, 'name' => 'Test User', 'email' => 'test@test.com', 'role' => 'admin',
        'phone' => '0700000000', 'address' => 'Test Address', 'kyc_status' => 'PENDING', 'kyc_notes' => '',
        '__route_key' => 'id', '__route_value' => 1
    ]],
    'admin/users/login-history' => [
        'user' => (object)['id' => 1, 'name' => 'Test User', '__route_key' => 'id', '__route_value' => 1],
        'loginHistory' => collect([]),
        'stats' => ['total_logins' => 0, 'successful' => 0, 'failed' => 0, 'unique_ips' => 0]
    ],
    
    // Admin Property Views
    'admin/properties/index' => ['properties' => collect([])],
    'admin/properties/show' => ['property' => (object)[
        'id' => 1, 'name' => 'Test Property', 'location' => 'Nairobi', 'description' => 'Test',
        'property_type' => 'apartment', 'bedrooms' => 2, 'bathrooms' => 1, 'max_guests' => 4,
        'price_per_night' => 5000, 'status' => 'ACTIVE', 'amenities' => '[]', 'photos' => [],
        'owner' => (object)['name' => 'Owner', 'email' => 'owner@test.com'],
        'bookings_count' => 0, 'total_revenue' => 0, 'average_rating' => 0,
        '__route_key' => 'id', '__route_value' => 1
    ]],
    'admin/properties/edit' => ['property' => (object)[
        'id' => 1, 'name' => 'Test Property', 'location' => 'Nairobi', 'description' => 'Test',
        'property_type' => 'apartment', 'bedrooms' => 2, 'bathrooms' => 1, 'max_guests' => 4,
        'price_per_night' => 5000, 'status' => 'ACTIVE', 'amenities' => '', 'owner_id' => 1,
        'photos' => [],
        '__route_key' => 'id', '__route_value' => 1
    ], 'owners' => []],
    
    // Admin Refund Views
    'admin/refunds/index' => ['refunds' => collect([]), 'stats' => ['pending' => 0, 'approved' => 0, 'processed' => 0, 'total_amount' => 0]],
    'admin/refunds/show' => ['refund' => (object)[
        'id' => 1, 'booking_id' => 1, 'amount' => 5000, 'reason' => 'Test', 'status' => 'PENDING',
        'created_at' => now(), 'approved_at' => null, 'approved_by' => null,
        'requester' => (object)['name' => 'Guest', 'email' => 'guest@test.com'],
        'booking' => (object)[
            'id' => 1, 'check_in_date' => now(), 'check_out_date' => now()->addDays(2),
            'total_amount' => 10000, 'status' => 'CONFIRMED',
            'property' => (object)['name' => 'Property'],
            'guest' => (object)['name' => 'Guest']
        ],
        '__route_key' => 'id', '__route_value' => 1
    ]],
    'admin/refunds/create' => [],
    
    // Admin Ticket Views
    'admin/tickets/index' => ['tickets' => collect([]), 'stats' => ['open' => 0, 'in_progress' => 0, 'resolved' => 0, 'urgent' => 0]],
    'admin/tickets/show' => ['ticket' => (object)[
        'id' => 1, 'ticket_number' => 'TKT-001', 'subject' => 'Test', 'message' => 'Test message',
        'category' => 'BOOKING', 'priority' => 'MEDIUM', 'status' => 'OPEN', 'booking_id' => null,
        'created_at' => now(), 'user' => (object)['name' => 'User', 'email' => 'user@test.com'],
        'replies' => collect([]),
        '__route_key' => 'id', '__route_value' => 1
    ], 'staffUsers' => []],
    
    // Admin Payout Views
    'admin/payouts/index' => ['payouts' => collect([]), 'stats' => ['pending' => 0, 'approved' => 0, 'total_pending_amount' => 0, 'total_completed_amount' => 0]],
    'admin/payouts/show' => ['payout' => (object)[
        'id' => 1, 'payout_ref' => 'PYT-001', 'payee_type' => 'OWNER', 'gross_amount' => 10000,
        'commission_amount' => 1000, 'deductions' => 0, 'net_amount' => 9000, 'status' => 'PENDING',
        'notes' => null, 'created_at' => now(), 'approved_at' => null, 'booking_id' => null,
        'payee' => (object)['name' => 'Owner', 'email' => 'owner@test.com'],
        'property' => null, 'booking' => null,
        '__route_key' => 'id', '__route_value' => 1
    ]],
    'admin/payouts/create' => ['users' => [], 'properties' => []],
    
    // Admin Report Views
    'admin/reports/index' => ['stats' => [
        'total_bookings' => 0, 'total_revenue' => 0, 'total_properties' => 0, 'total_users' => 0,
        'pending_refunds' => 0, 'open_tickets' => 0
    ]],
    'admin/reports/revenue' => [
        'summary' => ['total_revenue' => 0, 'total_bookings' => 0, 'average_booking_value' => 0, 'commission_earned' => 0],
        'monthlyRevenue' => [], 'propertyRevenue' => [], 'paymentMethods' => [], 'properties' => []
    ],
    'admin/reports/occupancy' => [
        'summary' => ['overall_occupancy' => 0, 'occupied_nights' => 0, 'available_nights' => 0, 'total_nights' => 0],
        'occupancyData' => [], 'dayOfWeekOccupancy' => [], 'peakPeriods' => [], 'lowPeriods' => [], 'properties' => []
    ],
    'admin/reports/cancellations' => [
        'summary' => ['total_cancellations' => 0, 'cancellation_rate' => 0, 'revenue_lost' => 0, 'avg_days_before' => 0],
        'cancellationReasons' => [], 'propertyCancellations' => [], 'cancellationTimeline' => [],
        'recentCancellations' => [], 'recommendations' => [], 'properties' => []
    ],
    'admin/reconciliation/index' => ['mismatches' => [], 'pending' => [], 'failed' => [], 'duplicates' => []],
    
    // Staff Views
    'staff/bookings/index' => ['bookings' => collect([])],
    'staff/bookings/show' => ['booking' => (object)[
        'id' => 1, 'check_in_date' => now(), 'check_out_date' => now()->addDays(2),
        'num_guests' => 2, 'special_requests' => null, 'total_amount' => 10000, 'amount_paid' => 10000,
        'status' => 'CONFIRMED', 'payment_status' => 'CONFIRMED', 'mpesa_receipt_number' => 'ABC123',
        'payment_method' => 'mpesa', 'created_at' => now(),
        'property' => (object)['name' => 'Property', 'location' => 'Nairobi', 'owner' => (object)['name' => 'Owner', 'email' => 'owner@test.com']],
        'guest' => (object)['name' => 'Guest', 'email' => 'guest@test.com', 'phone' => '0700000000'],
        '__route_key' => 'id', '__route_value' => 1
    ]],
    'staff/verification/index' => ['bookings' => collect([]), 'stats' => ['pending' => 0, 'verified_today' => 0, 'your_verifications' => 0]],
    'staff/verification/show' => ['booking' => (object)[
        'id' => 1, 'check_in_date' => now(), 'check_out_date' => now()->addDays(2),
        'total_amount' => 10000, 'amount_paid' => 10000, 'mpesa_receipt_number' => 'ABC123',
        'payment_status' => 'PENDING', 'updated_at' => now(),
        'property' => (object)['name' => 'Property'],
        'guest' => (object)['name' => 'Guest'],
        '__route_key' => 'id', '__route_value' => 1
    ]],
    'staff/tickets/index' => ['tickets' => collect([]), 'stats' => ['assigned_to_me' => 0, 'unassigned' => 0, 'in_progress' => 0]],
    'staff/tickets/show' => ['ticket' => (object)[
        'id' => 1, 'ticket_number' => 'TKT-001', 'subject' => 'Test', 'message' => 'Test message',
        'category' => 'BOOKING', 'priority' => 'MEDIUM', 'status' => 'OPEN', 'assigned_to' => null,
        'booking_id' => null, 'created_at' => now(), 'user' => (object)['name' => 'User', 'email' => 'user@test.com'],
        'replies' => collect([]),
        '__route_key' => 'id', '__route_value' => 1
    ]],
];

$passed = 0;
$failed = 0;
$errors = [];

foreach ($viewTests as $view => $data) {
    try {
        // Make all collections have pagination methods
        foreach ($data as $key => $value) {
            if ($value instanceof \Illuminate\Support\Collection) {
                $data[$key] = new \Illuminate\Pagination\LengthAwarePaginator(
                    $value, 
                    $value->count(), 
                    15, 
                    1
                );
            }
        }
        
        $rendered = view($view, $data)->render();
        
        if (strlen($rendered) > 100) {
            echo "✓ $view - PASSED (" . number_format(strlen($rendered)) . " bytes)\n";
            $passed++;
        } else {
            echo "✗ $view - FAILED (output too short)\n";
            $failed++;
            $errors[] = "$view: Output too short";
        }
    } catch (\Exception $e) {
        echo "✗ $view - ERROR: " . $e->getMessage() . "\n";
        $failed++;
        $errors[] = "$view: " . $e->getMessage();
    }
}

echo "\n=== TEST SUMMARY ===\n";
echo "Total Views: " . ($passed + $failed) . "\n";
echo "✓ Passed: $passed\n";
echo "✗ Failed: $failed\n";

if ($failed > 0) {
    echo "\n=== ERRORS ===\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
}

if ($failed == 0) {
    echo "\n🎉 ALL VIEWS RENDERING SUCCESSFULLY!\n";
}
