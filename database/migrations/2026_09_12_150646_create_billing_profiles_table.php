<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('billing_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('company_name')->nullable();
            $table->string('billing_email');
            $table->string('phone', 30)->nullable();
            $table->string('tax_number', 80)->nullable();
            $table->string('address');
            $table->string('city', 100);
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 30)->nullable();
            $table->string('country', 100);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('billing_profiles'); }
};
