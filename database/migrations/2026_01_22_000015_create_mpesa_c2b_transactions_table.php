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
        Schema::create('mpesa_c2b_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('booking_ref', 20);
            $table->string('trans_id', 20)->unique('uq_c2b_trans_id');
            $table->string('trans_time', 14);
            $table->decimal('trans_amount', 12, 2);
            $table->string('msisdn', 20)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('business_shortcode', 20)->nullable();
            $table->json('raw_payload');
            $table->timestamp('received_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_c2b_transactions');
    }
};
