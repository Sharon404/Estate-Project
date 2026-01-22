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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('payment_intent_id');
            $table->string('receipt_no', 30)->unique('uq_receipt_no');
            $table->string('mpesa_receipt_number', 20)->nullable();
            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('KES');
            $table->json('receipt_data');
            $table->timestamp('issued_at')->useCurrent();

            $table->foreign('booking_id', 'fk_receipt_booking')
                ->references('id')
                ->on('bookings');

            $table->foreign('payment_intent_id', 'fk_receipt_payment_intent')
                ->references('id')
                ->on('payment_intents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
