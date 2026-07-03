<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('department_id')->nullable()->after('branch_id')->index();
            $table->string('employee_id')->nullable()->after('id')->unique();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });

        Schema::create('user_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('from_company_id')->nullable();
            $table->uuid('to_company_id');
            $table->uuid('from_branch_id')->nullable();
            $table->uuid('to_branch_id');
            $table->uuid('from_department_id')->nullable();
            $table->uuid('to_department_id')->nullable();
            $table->string('reason')->nullable();
            $table->timestamp('effective_at');
            $table->uuid('created_by');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('to_branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_transfers');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'employee_id']);
        });
    }
};
