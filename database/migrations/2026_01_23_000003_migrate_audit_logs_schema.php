<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration handles the conversion from old audit_logs schema to new schema.
     * If the old table exists, it will be converted. If the new table already exists, it will be skipped.
     */
    public function up(): void
    {
        // Check if audit_logs table exists
        if (!Schema::hasTable('audit_logs')) {
            return; // Table will be created by 2026_01_23_000002 migration
        }

        // Check if the old schema exists (look for actor_type column)
        if (Schema::hasColumn('audit_logs', 'actor_type')) {
            // Old schema detected - migrate to new schema
            Schema::table('audit_logs', function (Blueprint $table) {
                // Add new columns
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->string('resource_type')->default('Unknown')->after('action');
                $table->unsignedBigInteger('resource_id')->nullable()->after('resource_type');
                $table->json('changes')->nullable()->after('resource_id');
                $table->json('metadata')->nullable()->after('changes');
                $table->string('user_role')->nullable()->after('user_agent');
                $table->string('status')->default('success')->after('user_role');
                $table->text('error_message')->nullable()->after('description');
            });

            // Migrate data from old columns to new columns
            \DB::statement('UPDATE audit_logs SET user_id = actor_id WHERE actor_type = "USER"');
            \DB::statement('UPDATE audit_logs SET resource_type = entity_type');
            \DB::statement('UPDATE audit_logs SET resource_id = entity_id');
            \DB::statement('UPDATE audit_logs SET metadata = JSON_OBJECT("old_values", JSON_EXTRACT(old_values, "$"), "new_values", JSON_EXTRACT(new_values, "$")) WHERE old_values IS NOT NULL OR new_values IS NOT NULL');
            
            // Drop old columns
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->dropColumn(['actor_type', 'actor_id', 'actor_role', 'entity_type', 'entity_id', 'old_values', 'new_values']);
            });

            // Re-add indexes for new schema
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->index('user_id');
                $table->index('resource_type');
                $table->index(['resource_type', 'resource_id']);
                $table->index(['user_id', 'created_at']);
                $table->index(['status', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a data migration - cannot safely reverse
        // Manual intervention required if rollback is needed
    }
};
