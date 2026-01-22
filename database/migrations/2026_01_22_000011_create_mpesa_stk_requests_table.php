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
        Schema::create('mpesa_stk_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_intent_id');
            $table->string('phone_e164', 20);
            $table->string('business_shortcode', 20);
            $table->string('account_reference', 50);
            $table->string('transaction_desc', 255)->nullable();
            $table->string('merchant_request_id', 50)->nullable();
            $table->string('checkout_request_id', 50)->unique('uq_stk_checkout_request')->nullable();
            $table->json('request_payload');
            $table->json('response_payload')->nullable();
            $table->enum('status', [
                'REQUESTED',
                'ACCEPTED',
                'CALLBACK_RECEIVED',
                'SUCCESS',
                'FAILED',
                'TIMEOUT'
            ])->default('REQUESTED');
            $table->timestamps();

            $table->foreign('payment_intent_id', 'fk_stk_request_payment_intent')
                ->references('id')
                ->on('payment_intents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_stk_requests');
    }
};
