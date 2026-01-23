<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tracks all outgoing emails for audit trail and resend capability.
     * Status flow: PENDING → SENT | FAILED → (can be retried)
     */
    public function up(): void
    {
        Schema::create('email_outbox', function (Blueprint $table) {
            $table->id();
            
            // Email details
            $table->string('recipient_email', 255);  // Guest/admin email
            $table->string('subject', 255);          // Email subject
            $table->longText('body');                // Email body (HTML)
            $table->json('metadata')->nullable();    // Additional data (booking_ref, receipt_no, etc)
            
            // Linkage
            $table->unsignedBigInteger('receipt_id')->nullable();  // Link to receipt
            $table->unsignedBigInteger('booking_id')->nullable();  // Link to booking
            
            // Status tracking
            $table->enum('status', ['PENDING', 'SENT', 'FAILED'])->default('PENDING')->index();
            $table->text('error_message')->nullable();  // Error details if FAILED
            $table->integer('retry_count')->default(0); // Number of retry attempts
            $table->integer('max_retries')->default(3); // Max retry attempts allowed
            
            // Timestamps
            $table->timestamp('sent_at')->nullable();   // When email was actually sent
            $table->timestamp('last_retry_at')->nullable();  // Last retry attempt
            $table->timestamps();  // created_at, updated_at
            
            // Indexes
            $table->index(['recipient_email', 'status']);
            $table->index(['booking_id', 'status']);
            $table->index(['receipt_id', 'status']);
            $table->index(['status', 'created_at']);
            
            // Foreign keys
            $table->foreign('receipt_id')
                ->references('id')
                ->on('receipts')
                ->onDelete('set null');
            
            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_outbox');
    }
};
