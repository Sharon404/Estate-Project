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
        Schema::create('mpesa_stk_callbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stk_request_id');
            $table->integer('result_code');
            $table->string('result_desc', 255);
            $table->string('mpesa_receipt_number', 20)->nullable();
            $table->string('transaction_date', 14)->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('phone_e164', 20)->nullable();
            $table->json('raw_payload');
            $table->timestamp('received_at')->useCurrent();

            $table->foreign('stk_request_id', 'fk_stk_callback_request')
                ->references('id')
                ->on('mpesa_stk_requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_stk_callbacks');
    }
};
