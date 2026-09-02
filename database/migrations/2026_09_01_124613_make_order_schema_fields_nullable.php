<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Make admin fields nullable so frontend checkout inserts succeed
            if (Schema::hasColumn('orders', 'order_no')) {
                $table->string('order_no')->nullable()->change();
            }
            if (Schema::hasColumn('orders', 'order_date')) {
                $table->date('order_date')->nullable()->change();
            }
            if (Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->change();
            }
            if (Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->change();
            }
            if (Schema::hasColumn('orders', 'delivery_method')) {
                $table->string('delivery_method')->nullable()->change();
            }
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->nullable()->change();
            }
            if (Schema::hasColumn('orders', 'fulfillment_status')) {
                $table->string('fulfillment_status')->nullable()->change();
            }
            if (Schema::hasColumn('orders', 'delivery_status')) {
                $table->string('delivery_status')->nullable()->change();
            }
            if (Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        //
    }
};
