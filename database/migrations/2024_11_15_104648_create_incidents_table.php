<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE TABLE incident_reporting (
                incident_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                veh_id INT UNSIGNED,
                incident_date DATE NOT NULL,
                description TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (veh_id) REFERENCES vehicles(id) ON DELETE CASCADE
            )
        ");
    }

    public function down()
    {
        DB::statement('DROP TABLE IF EXISTS incident_reporting');
    }
};
