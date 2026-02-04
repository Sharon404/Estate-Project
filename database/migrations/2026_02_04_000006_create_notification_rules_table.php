<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('event'); // 'booking_created', 'payment_received', 'ticket_created', etc.
            $table->json('channels'); // ['email', 'sms']
            $table->json('recipients'); // ['admin', 'staff', 'customer']
            $table->string('template')->nullable();
            $table->json('conditions')->nullable(); // Additional conditions
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_rules');
    }
};
