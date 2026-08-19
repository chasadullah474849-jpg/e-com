<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create('category_subcategory', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('category_id');

            $table->unsignedBigInteger('subcategory_id');

            $table->timestamps();


            $table->index('category_id');

            $table->index('subcategory_id');

        });

    }


    public function down(): void
    {
        Schema::dropIfExists('category_subcategory');
    }

};
