<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $definitions = [
            'phone' => fn (Blueprint $table) => $table->string('phone', 30)->nullable(),
            'address' => fn (Blueprint $table) => $table->text('address')->nullable(),
            'city' => fn (Blueprint $table) => $table->string('city', 100)->nullable(),
            'country' => fn (Blueprint $table) => $table->string('country', 100)->nullable(),
            'bio' => fn (Blueprint $table) => $table->text('bio')->nullable(),
            'avatar' => fn (Blueprint $table) => $table->string('avatar')->nullable(),
            'theme' => fn (Blueprint $table) => $table->string('theme', 20)->default('light'),
            'language' => fn (Blueprint $table) => $table->string('language', 10)->default('en'),
            'email_notify' => fn (Blueprint $table) => $table->boolean('email_notify')->default(true),
            'company_name' => fn (Blueprint $table) => $table->string('company_name')->nullable(),
            'billing_email' => fn (Blueprint $table) => $table->string('billing_email')->nullable(),
            'vat_number' => fn (Blueprint $table) => $table->string('vat_number', 100)->nullable(),
        ];

        foreach ($definitions as $column => $definition) {
            if (! Schema::hasColumn('users', $column)) {
                Schema::table('users', $definition);
            }
        }
    }

    public function down(): void
    {
        // Intentionally empty: this migration safely skips pre-existing columns,
        // so automatically dropping them could delete existing user data.
    }
};
