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
        ALTER TABLE customers
        DROP COLUMN address,
        DROP COLUMN city,
        DROP COLUMN country;
    ");
}

public function down()
{
    DB::statement("
        ALTER TABLE customers
        ADD address VARCHAR(255),
        ADD city VARCHAR(100),
        ADD country VARCHAR(100);
    ");
}

};