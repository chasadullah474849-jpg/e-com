<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('orders')) return;
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'billing_status')) $table->string('billing_status', 30)->default('pending')->index();
            if (!Schema::hasColumn('orders', 'payment_method')) $table->string('payment_method', 50)->nullable();
            if (!Schema::hasColumn('orders', 'paid_at')) $table->timestamp('paid_at')->nullable();
        });
    }
    public function down(): void {
        if (!Schema::hasTable('orders')) return;
        Schema::table('orders', function (Blueprint $table) {
            $columns = array_values(array_filter(['billing_status','payment_method','paid_at'], fn($c) => Schema::hasColumn('orders',$c)));
            if ($columns) $table->dropColumn($columns);
        });
    }
};
