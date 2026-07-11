<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_transfers', function (Blueprint $table) {
            $table->uuid('from_workspace_id')->nullable()->after('user_id');
            $table->uuid('to_workspace_id')->nullable()->after('from_workspace_id');
            $table->json('previous_state')->nullable()->after('to_department_id');
            $table->json('new_state')->nullable()->after('previous_state');
            $table->uuid('transferred_by')->nullable()->after('new_state');
            $table->timestamp('transferred_at')->useCurrent()->after('transferred_by');
            $table->softDeletes();

            $table->foreign('transferred_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('user_transfers', function (Blueprint $table) {
            $table->dropForeign(['transferred_by']);
            $table->dropColumn([
                'from_workspace_id',
                'to_workspace_id',
                'previous_state',
                'new_state',
                'transferred_by',
                'transferred_at',
                'deleted_at'
            ]);
        });
    }
};
