<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE categories
            MODIFY status ENUM('active','inactive')
            NOT NULL DEFAULT 'active'
        ");

        DB::statement("
            UPDATE categories
            SET status =
                CASE
                    WHEN status = '1' THEN 'active'
                    WHEN status = '0' THEN 'inactive'
                    ELSE status
                END
        ");
    }

    public function down()
    {
        //
    }
};
