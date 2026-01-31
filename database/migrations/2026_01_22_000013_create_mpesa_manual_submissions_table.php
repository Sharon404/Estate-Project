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
        Schema::create('mpesa_manual_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('mpesa_receipt_number', 20)->unique('uq_manual_mpesa_receipt');
            $table->string('phone_e164', 20)->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['SUBMITTED', 'VERIFIED', 'REJECTED'])->default('SUBMITTED');
            $table->text('raw_notes')->nullable();
            $table->timestamps();

            $table->foreign('booking_id', 'fk_manual_submission_booking')
                ->references('id')
                ->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_manual_submissions');
    }
};
