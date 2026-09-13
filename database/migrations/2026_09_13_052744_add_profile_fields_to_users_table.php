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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'theme')) {
                $table->string('theme')->default('light')->nullable();
            }
            if (! Schema::hasColumn('users', 'language')) {
                $table->string('language')->default('en')->nullable();
            }
            if (! Schema::hasColumn('users', 'email_notify')) {
                $table->boolean('email_notify')->default(true)->nullable();
            }
            if (! Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable();
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable();
            }
            if (! Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable();
            }
            if (! Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable();
            }
            if (! Schema::hasColumn('users', 'vat_number')) {
                $table->string('vat_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'theme')) {
                $table->dropColumn('theme');
            }
            if (Schema::hasColumn('users', 'language')) {
                $table->dropColumn('language');
            }
            if (Schema::hasColumn('users', 'email_notify')) {
                $table->dropColumn('email_notify');
            }
            if (Schema::hasColumn('users', 'company_name')) {
                $table->dropColumn('company_name');
            }
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('users', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('users', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('users', 'vat_number')) {
                $table->dropColumn('vat_number');
            }
        });
    }
};
