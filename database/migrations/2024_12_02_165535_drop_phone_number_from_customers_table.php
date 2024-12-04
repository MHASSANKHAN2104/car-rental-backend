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
        DROP COLUMN phone_number;
    ");
}

public function down()
{
    DB::statement("
        ALTER TABLE customers
        ADD phone_number VARCHAR(20) NOT NULL;
    ");
}

};
