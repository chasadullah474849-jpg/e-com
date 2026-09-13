<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('avatar');
        });
    }
    public function down(): void {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['username','phone','avatar','bio']));
    }
};
