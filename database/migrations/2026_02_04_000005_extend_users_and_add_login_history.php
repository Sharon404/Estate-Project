<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('kyc_status')->default('PENDING')->after('address'); // PENDING, VERIFIED, REJECTED
            $table->timestamp('kyc_verified_at')->nullable()->after('kyc_status');
            $table->foreignId('kyc_verified_by')->nullable()->after('kyc_verified_at')->constrained('users')->nullOnDelete();
            $table->text('kyc_notes')->nullable()->after('kyc_verified_by');
        });

        Schema::create('login_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable(); // 'desktop', 'mobile', 'tablet'
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->boolean('successful')->default(true);
            $table->string('failure_reason')->nullable();
            $table->timestamp('login_at');
            $table->timestamp('logout_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'login_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_history');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kyc_verified_by');
            $table->dropColumn(['phone', 'address', 'kyc_status', 'kyc_verified_at', 'kyc_notes']);
        });
    }
};
