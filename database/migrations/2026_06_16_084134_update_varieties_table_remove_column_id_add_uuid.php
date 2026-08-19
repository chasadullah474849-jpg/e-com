<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('varieties', function (Blueprint $table) {

            // Remove column_id
            if (Schema::hasColumn('varieties', 'column_id')) {
                $table->dropColumn('column_id');
            }

            // Add uuid column
            if (!Schema::hasColumn('varieties', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('varieties', function (Blueprint $table) {

            // Remove uuid
            if (Schema::hasColumn('varieties', 'uuid')) {
                $table->dropColumn('uuid');
            }

            // Restore column_id
            if (!Schema::hasColumn('varieties', 'column_id')) {
                $table->unsignedBigInteger('column_id')->nullable();
            }
        });
    }
};
