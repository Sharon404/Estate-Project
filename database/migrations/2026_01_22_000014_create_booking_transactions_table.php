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
        Schema::create('booking_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('payment_intent_id')->nullable();
            $table->enum('type', ['PAYMENT', 'REFUND', 'ADJUSTMENT']);
            $table->enum('source', ['MPESA_STK', 'MPESA_MANUAL', 'ADMIN']);
            $table->string('external_ref', 50)->unique('uq_booking_tx_external_ref');
            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('KES');
            $table->json('meta')->nullable();
            $table->timestamp('posted_at')->useCurrent();

            $table->foreign('booking_id', 'fk_booking_tx_booking')
                ->references('id')
                ->on('bookings');

            $table->foreign('payment_intent_id', 'fk_booking_tx_payment_intent')
                ->references('id')
                ->on('payment_intents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_transactions');
    }
};
