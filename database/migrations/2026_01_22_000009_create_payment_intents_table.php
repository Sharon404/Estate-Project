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
        Schema::create('payment_intents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('intent_ref', 30)->unique('uq_payment_intent_ref');
            $table->enum('method', ['MPESA_STK', 'MPESA_MANUAL']);
            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('KES');
            $table->enum('status', [
                'INITIATED',
                'PENDING',
                'SUCCEEDED',
                'FAILED',
                'UNDER_REVIEW',
                'CANCELLED'
            ])->default('INITIATED');
            $table->string('created_by', 20)->default('SYSTEM');
            $table->timestamps();

            $table->foreign('booking_id', 'fk_payment_intents_booking')
                ->references('id')
                ->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_intents');
    }
};
