<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->uuid('workspace_id')->nullable()->after('id')->index();
            $table->uuid('department_id')->nullable()->after('branch_id')->index();
            $table->uuid('user_id')->nullable()->after('department_id')->index();

            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->dropUnique(['company_id', 'branch_id', 'key']);
            $table->unique(['workspace_id', 'company_id', 'branch_id', 'department_id', 'user_id', 'key'], 'settings_hierarchy_unique');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique('settings_hierarchy_unique');
            $table->dropForeign(['user_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['workspace_id']);
            $table->dropColumn(['workspace_id', 'department_id', 'user_id']);
            $table->unique(['company_id', 'branch_id', 'key']);
        });
    }
};
