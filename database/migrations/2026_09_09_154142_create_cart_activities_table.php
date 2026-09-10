<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_activities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('session_id')->nullable();

            $table->unsignedInteger('quantity')->default(1);

            $table->decimal('product_price', 12, 2)->default(0);

            $table->decimal('total_amount', 12, 2)->default(0);

            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->nullOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_activities');
    }
};
