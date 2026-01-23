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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            
            // User who performed the action
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Action details
            $table->string('action'); // booking_created, payment_initiated, etc.
            $table->string('resource_type'); // Booking, Payment, Receipt, etc.
            $table->unsignedBigInteger('resource_id')->nullable();
            
            // Change tracking
            $table->json('changes')->nullable(); // {before: {...}, after: {...}}
            $table->json('metadata')->nullable(); // Additional context
            
            // Request information
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('user_role')->nullable(); // admin, guest, system
            
            // Status and description
            $table->string('status')->default('success'); // success, failed, pending
            $table->text('description')->nullable();
            $table->text('error_message')->nullable();
            
            // Timestamps
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('action');
            $table->index('resource_type');
            $table->index('resource_id');
            $table->index('ip_address');
            $table->index(['resource_type', 'resource_id']);
            $table->index(['action', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
            
            // Foreign key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
