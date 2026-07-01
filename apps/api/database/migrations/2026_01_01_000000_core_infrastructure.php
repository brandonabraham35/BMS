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
        Schema::create('companies', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->string('name');
            $blueprint->string('slug')->unique();
            $blueprint->string('email')->nullable();
            $blueprint->string('phone')->nullable();
            $blueprint->string('address')->nullable();
            $blueprint->string('website')->nullable();
            $blueprint->string('logo')->nullable();
            $blueprint->boolean('is_active')->default(true);
            $blueprint->json('settings')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });

        Schema::create('branches', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->uuid('company_id')->index();
            $blueprint->string('name');
            $blueprint->string('email')->nullable();
            $blueprint->string('phone')->nullable();
            $blueprint->string('address')->nullable();
            $blueprint->boolean('is_active')->default(true);
            $blueprint->timestamps();
            $blueprint->softDeletes();

            $blueprint->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('roles', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->uuid('company_id')->nullable()->index();
            $blueprint->string('name');
            $blueprint->string('slug');
            $blueprint->string('description')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();

            $blueprint->unique(['company_id', 'slug']);
            $blueprint->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('permissions', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->string('name');
            $blueprint->string('slug')->unique(); // e.g. users.view
            $blueprint->string('group'); // e.g. Identity
            $blueprint->string('description')->nullable();
            $blueprint->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $blueprint) {
            $blueprint->uuid('role_id')->index();
            $blueprint->uuid('permission_id')->index();
            $blueprint->timestamps();

            $blueprint->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $blueprint->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $blueprint->primary(['role_id', 'permission_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('company_id')->nullable()->after('id')->index();
            $table->uuid('branch_id')->nullable()->after('company_id')->index();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        });

        Schema::create('user_roles', function (Blueprint $blueprint) {
            $blueprint->uuid('user_id')->index();
            $blueprint->uuid('role_id')->index();
            $blueprint->timestamps();

            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $blueprint->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $blueprint->primary(['user_id', 'role_id']);
        });

        Schema::create('settings', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->uuid('company_id')->nullable()->index();
            $blueprint->uuid('branch_id')->nullable()->index();
            $blueprint->string('key')->index();
            $blueprint->text('value')->nullable();
            $blueprint->string('type')->default('string');
            $blueprint->string('group')->default('general');
            $blueprint->timestamps();

            $blueprint->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $blueprint->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $blueprint->unique(['company_id', 'branch_id', 'key']);
        });

        Schema::create('notifications', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->uuid('company_id')->nullable()->index();
            $blueprint->uuid('user_id')->index();
            $blueprint->string('type');
            $blueprint->text('data');
            $blueprint->timestamp('read_at')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();

            $blueprint->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('audit_logs', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->uuid('company_id')->nullable()->index();
            $blueprint->uuid('user_id')->nullable()->index();
            $blueprint->string('action'); // created, updated, deleted, login, etc
            $blueprint->string('entity_type');
            $blueprint->uuid('entity_id');
            $blueprint->json('old_values')->nullable();
            $blueprint->json('new_values')->nullable();
            $blueprint->string('ip_address')->nullable();
            $blueprint->string('user_agent')->nullable();
            $blueprint->timestamps();

            $blueprint->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('user_roles');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['company_id', 'branch_id', 'deleted_at']);
        });
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('companies');
    }
};
