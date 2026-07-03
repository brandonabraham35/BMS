<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('legal_name')->nullable()->after('name');
            $table->string('registration_number')->nullable()->after('legal_name');
            $table->string('tax_number')->nullable()->after('registration_number');
            $table->string('vat_number')->nullable()->after('tax_number');
            $table->string('business_type')->nullable()->after('vat_number');
            $table->string('industry')->nullable()->after('business_type');
            $table->string('country')->nullable()->after('industry');
            $table->string('currency', 3)->default('USD')->after('country');
            $table->string('timezone')->default('UTC')->after('currency');
            $table->string('language', 5)->default('en')->after('timezone');
            $table->string('fiscal_year')->nullable()->after('language');
            $table->json('business_hours')->nullable()->after('fiscal_year');
            $table->string('status')->default('active')->after('business_hours');
            $table->date('date_founded')->nullable()->after('status');
            $table->text('description')->nullable()->after('date_founded');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->string('code')->nullable()->after('name');
            $table->uuid('manager_id')->nullable()->after('address');
            $table->string('timezone')->default('UTC')->after('manager_id');
            $table->json('working_hours')->nullable()->after('timezone');
            $table->string('status')->default('active')->after('working_hours');

            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['code', 'manager_id', 'timezone', 'working_hours', 'status']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'legal_name', 'registration_number', 'tax_number', 'vat_number',
                'business_type', 'industry', 'country', 'currency', 'timezone',
                'language', 'fiscal_year', 'business_hours', 'status', 'date_founded', 'description'
            ]);
        });
    }
};
