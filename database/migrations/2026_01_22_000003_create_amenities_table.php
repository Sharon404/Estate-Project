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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique('uq_amenity_code');
            $table->string('name');
            $table->enum('pricing_model', ['PER_BOOKING', 'PER_NIGHT', 'PER_PERSON', 'FIXED']);
            $table->decimal('unit_price', 12, 2);
            $table->char('currency', 3)->default('KES');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
