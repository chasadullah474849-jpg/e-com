<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ADD UUID ONLY IF IT DOES NOT EXIST
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('blogs', 'uuid')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->uuid('uuid')
                    ->nullable()
                    ->unique()
                    ->after('id');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | ADD NAME ONLY IF IT DOES NOT EXIST
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('blogs', 'name')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->string('name')
                    ->nullable()
                    ->after('uuid');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | ADD DESCRIPTION ONLY IF IT DOES NOT EXIST
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('blogs', 'description')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->text('description')
                    ->nullable()
                    ->after('image');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | GENERATE UUID FOR EXISTING BLOGS
        |--------------------------------------------------------------------------
        */

        DB::table('blogs')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(100, function ($blogs) {
                foreach ($blogs as $blog) {
                    DB::table('blogs')
                        ->where('id', $blog->id)
                        ->update([
                            'uuid' => (string) Str::uuid(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        /*
         * No columns are removed here because some of these columns
         * already existed before this migration.
         */
    }
};
