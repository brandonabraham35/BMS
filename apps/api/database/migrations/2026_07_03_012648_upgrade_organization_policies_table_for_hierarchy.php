<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_policies', function (Blueprint $table) {
            $table->uuid('workspace_id')->nullable()->after('id')->index();
            $table->uuid('branch_id')->nullable()->after('company_id')->index();

            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('organization_policies', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['workspace_id']);
            $table->dropColumn(['workspace_id', 'branch_id']);
        });
    }
};
