<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE booking_transactions MODIFY COLUMN source ENUM('MPESA_STK', 'MPESA_MANUAL', 'MPESA_C2B', 'ADMIN') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE booking_transactions MODIFY COLUMN source ENUM('MPESA_STK', 'MPESA_MANUAL', 'ADMIN') NOT NULL");
    }
};
