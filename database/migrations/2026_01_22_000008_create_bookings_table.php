<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_ref', 20)->unique('uq_booking_ref');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('guest_id');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->text('special_requests')->nullable();
            $table->enum('status', [
                'DRAFT',
                'PENDING_PAYMENT',
                'PARTIALLY_PAID',
                'PAID',
                'CANCELLED',
                'EXPIRED'
            ])->default('DRAFT');
            $table->char('currency', 3)->default('KES');
            $table->decimal('nightly_rate', 12, 2);
            $table->integer('nights');
            $table->decimal('accommodation_subtotal', 12, 2);
            $table->decimal('addons_subtotal', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('amount_due', 12, 2)->default(0);
            $table->decimal('minimum_deposit', 12, 2)->nullable();
            $table->timestamps();

            $table->foreign('property_id', 'fk_bookings_property')
                ->references('id')
                ->on('properties');

            $table->foreign('guest_id', 'fk_bookings_guest')
                ->references('id')
                ->on('guests');

            $table->index('status', 'idx_bookings_status');
            $table->index(['property_id', 'check_in', 'check_out'], 'idx_bookings_dates');

            // Check constraint
            $table->comment('CHECK (check_out > check_in)');
        });

        // Add check constraint for MySQL
        DB::statement('ALTER TABLE bookings ADD CONSTRAINT chk_booking_dates CHECK (check_out > check_in)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
