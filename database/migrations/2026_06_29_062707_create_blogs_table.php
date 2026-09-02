<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('blogs', function (Blueprint $table) {

        $table->id();

        $table->uuid('uuid')->unique();

        $table->string('name');

        $table->string('title');

        $table->text('description');

        $table->string('image')->nullable();

        $table->enum('status', [
            'draft',
            'published'
        ])->default('draft');

        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
