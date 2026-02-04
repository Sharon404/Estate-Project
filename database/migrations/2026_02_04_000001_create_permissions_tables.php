<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Permissions - granular abilities
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'view-bookings', 'edit-refunds', 'delete-users'
            $table->string('category'); // e.g., 'bookings', 'payments', 'users', 'properties'
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Role has many permissions
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role'); // 'admin', 'staff', etc.
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['role', 'permission_id']);
        });

        // User-specific permission overrides (optional)
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->boolean('granted')->default(true); // true = grant, false = revoke
            $table->timestamps();
            
            $table->unique(['user_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
    }
};
