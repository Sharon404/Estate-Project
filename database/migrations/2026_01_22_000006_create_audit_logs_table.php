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
            $table->enum('actor_type', ['USER', 'SYSTEM']);
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('actor_role', 50)->nullable();
            $table->string('action', 100);
            $table->string('entity_type', 50);
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['entity_type', 'entity_id'], 'idx_audit_entity');
            $table->index(['actor_type', 'actor_id'], 'idx_audit_actor');
            $table->index('action', 'idx_audit_action');
            $table->index('created_at', 'idx_audit_created');
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
