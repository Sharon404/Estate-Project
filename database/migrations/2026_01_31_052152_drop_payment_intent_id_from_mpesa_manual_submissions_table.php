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
        Schema::table('mpesa_manual_submissions', function (Blueprint $table) {
            // Drop the foreign key first
            if (Schema::hasColumn('mpesa_manual_submissions', 'payment_intent_id')) {
                $table->dropForeign('fk_manual_submission_payment_intent');
                $table->dropColumn('payment_intent_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpesa_manual_submissions', function (Blueprint $table) {
            // Restore payment_intent_id
            $table->unsignedBigInteger('payment_intent_id')->nullable();
        });
    }
};
