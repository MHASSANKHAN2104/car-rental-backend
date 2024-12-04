<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
            CREATE TABLE statuses (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                status VARCHAR(50) NOT NULL UNIQUE
            );
        ");
    }

    public function down()
    {
        DB::statement("DROP TABLE IF EXISTS statuses;");
    }

};
