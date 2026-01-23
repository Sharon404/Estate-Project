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
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'status')) {
                $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])
                    ->default('APPROVED')
                    ->after('is_active');
            }

            if (!Schema::hasColumn('properties', 'pending_removal')) {
                $table->boolean('pending_removal')
                    ->default(false)
                    ->after('status');
            }

            if (!Schema::hasColumn('properties', 'requested_by')) {
                $table->unsignedBigInteger('requested_by')->nullable()->after('pending_removal');
            }

            if (!Schema::hasColumn('properties', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('requested_by');
            }

            $table->index('status');
            $table->index(['status', 'pending_removal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'approved_by')) {
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('properties', 'requested_by')) {
                $table->dropColumn('requested_by');
            }
            if (Schema::hasColumn('properties', 'pending_removal')) {
                $table->dropColumn('pending_removal');
            }
            if (Schema::hasColumn('properties', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
