<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Str;

class BookingReferenceService
{
    /**
     * Generate a unique booking reference.
     * Format: BK + YYYYMMDD + 5-character random alphanumeric suffix
     * Example: BK202601221A3K9
     *
     * @return string
     * @throws \Exception If unable to generate unique reference after max attempts
     */
    public function generate(): string
    {
        $maxAttempts = 10;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $reference = $this->buildReference();

            if (!$this->exists($reference)) {
                return $reference;
            }

            $attempt++;
        }

        throw new \Exception(
            "Failed to generate unique booking reference after {$maxAttempts} attempts"
        );
    }

    /**
     * Build a booking reference string.
     *
     * @return string
     */
    private function buildReference(): string
    {
        $prefix = 'BK';
        $dateSegment = now()->format('Ymd'); // YYYYMMDD
        $randomSuffix = Str::random(5); // 5 random alphanumeric chars

        return $prefix . $dateSegment . $randomSuffix;
    }

    /**
     * Check if booking reference already exists in database.
     *
     * @param string $reference
     * @return bool
     */
    private function exists(string $reference): bool
    {
        return Booking::where('booking_ref', $reference)->exists();
    }
}
