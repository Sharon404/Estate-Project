<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_ref')->unique();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->string('payee_type'); // 'OWNER', 'STAFF', 'COMMISSION'
            $table->string('payee_name');
            $table->string('payee_phone')->nullable();
            $table->string('payee_account')->nullable();
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->string('status')->default('PENDING'); // PENDING, APPROVED, PROCESSING, COMPLETED, DISPUTED, REJECTED
            $table->string('payment_method')->nullable(); // 'MPESA', 'BANK_TRANSFER', 'CASH'
            $table->string('mpesa_ref')->nullable();
            $table->text('notes')->nullable();
            $table->text('dispute_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
