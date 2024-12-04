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
        CREATE TABLE mileages (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            vehicle_id INT UNSIGNED,
            mileage VARCHAR(100),
            recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
        );
    ");
}

public function down()
{
    DB::statement("DROP TABLE IF EXISTS mileages;");
}

};
