<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMaintenanceTable extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE TABLE maintenance (
                main_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                veh_id INT UNSIGNED,
                maintenance_date DATE NOT NULL,
                description TEXT NOT NULL,
                cost DECIMAL(10, 2) NOT NULL,
                status ENUM('UNDER MAINTENANCE','MAINTENANCE DONE') DEFAULT 'MAINTENANCE DONE',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (veh_id) REFERENCES vehicles(id) ON DELETE CASCADE
            )
        ");
    }

    public function down()
    {
        DB::statement('DROP TABLE IF EXISTS maintenance');
    }
}
