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
        Schema::table('payment_intents', function (Blueprint $table) {
            // Add metadata column if it doesn't exist
            if (!Schema::hasColumn('payment_intents', 'metadata')) {
                $table->json('metadata')->nullable()->after('created_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_intents', function (Blueprint $table) {
            if (Schema::hasColumn('payment_intents', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }
};
