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
            // Add submitted_at timestamp if it doesn't exist
            if (!Schema::hasColumn('mpesa_manual_submissions', 'submitted_at')) {
                $table->timestamp('submitted_at')->useCurrent();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpesa_manual_submissions', function (Blueprint $table) {
            // Drop submitted_at if it exists
            if (Schema::hasColumn('mpesa_manual_submissions', 'submitted_at')) {
                $table->dropColumn('submitted_at');
            }
        });
    }
};
