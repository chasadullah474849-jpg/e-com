<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->string('main_title');
            $table->string('name');
            $table->text('description');

            $table->enum('status', ['active', 'inactive'])
                  ->default('active');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
