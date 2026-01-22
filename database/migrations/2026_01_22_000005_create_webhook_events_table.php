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
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 20);
            $table->string('event_type', 50);
            $table->string('idempotency_key', 100)->nullable();
            $table->json('payload');
            $table->enum('process_status', ['PENDING', 'PROCESSED', 'FAILED'])->default('PENDING');
            $table->text('error_message')->nullable();
            $table->timestamp('received_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
    }
};
