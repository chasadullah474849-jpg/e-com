<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stripe_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('stripe_payment_intent_id')
                ->unique();

            $table->string('stripe_payment_method_id')
                ->nullable();

            $table->string('customer_name');
            $table->string('customer_email');

            // Stored in smallest currency unit: 1000 = $10.00
            $table->unsignedBigInteger('amount');

            $table->string('currency', 10)
                ->default('usd');

            $table->string('status')
                ->default('pending');

            $table->text('failure_message')
                ->nullable();

            $table->json('metadata')
                ->nullable();

            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_payments');
    }
};
