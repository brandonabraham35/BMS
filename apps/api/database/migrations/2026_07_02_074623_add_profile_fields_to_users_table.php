<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('active')->index(); // pending, invited, active, suspended, locked, disabled, archived
            $table->string('display_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('language')->default('en');
            $table->string('locale')->default('en_US');
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->json('preferences')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->boolean('mfa_enabled')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status', 'display_name', 'first_name', 'last_name', 'phone',
                'timezone', 'language', 'locale', 'job_title', 'department',
                'bio', 'profile_photo', 'preferences', 'last_login_at',
                'last_login_ip', 'password_changed_at', 'mfa_enabled'
            ]);
        });
    }
};
