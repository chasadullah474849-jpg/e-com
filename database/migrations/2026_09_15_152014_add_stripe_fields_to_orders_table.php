<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('status');
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status', 30)->default('pending')->after('payment_method');
            }

            if (!Schema::hasColumn('orders', 'stripe_payment_intent_id')) {
                $table->string('stripe_payment_intent_id')->nullable()->unique()->after('payment_status');
            }

            if (!Schema::hasColumn('orders', 'checkout_token')) {
                $table->uuid('checkout_token')->nullable()->unique()->after('stripe_payment_intent_id');
            }

            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('checkout_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'payment_method',
                'payment_status',
                'stripe_payment_intent_id',
                'checkout_token',
                'paid_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
