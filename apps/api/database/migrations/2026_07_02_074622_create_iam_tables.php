<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->index();
            $table->uuid('invited_by')->index();
            $table->string('email')->index();
            $table->string('token')->unique();
            $table->string('role_slug')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('invited_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('password_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('password');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('trusted_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('device_id')->index(); // Fingerprint
            $table->string('name')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->boolean('is_trusted')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'device_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trusted_devices');
        Schema::dropIfExists('password_histories');
        Schema::dropIfExists('invitations');
    }
};
