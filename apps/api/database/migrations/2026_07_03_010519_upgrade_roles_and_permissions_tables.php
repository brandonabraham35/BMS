<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->uuid('workspace_id')->nullable()->after('id')->index();
            $table->uuid('branch_id')->nullable()->after('company_id')->index();
            $table->string('status')->default('active')->after('description');
            $table->boolean('is_system')->default(false)->after('status');
            $table->integer('version')->default(1)->after('is_system');

            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->uuid('group_id')->nullable()->after('id')->index();
            $table->json('metadata')->nullable()->after('description');
            $table->integer('version')->default(1)->after('metadata');

            $table->foreign('group_id')->references('id')->on('permission_groups')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn(['group_id', 'metadata', 'version']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['workspace_id']);
            $table->dropColumn(['workspace_id', 'branch_id', 'status', 'is_system', 'version']);
        });
    }
};
