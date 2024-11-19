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
        DB::statement("CREATE TABLE rental
        (
        rental_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        veh_id INT UNSIGNED ,
        cus_id INT UNSIGNED ,
        status ENUM('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
        requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        approved_at TIMESTAMP NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        total_price DECIMAL (10,2)NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        FOREIGN KEY (veh_id) REFERENCES vehicles(id) ON DELETE CASCADE,
        FOREIGN KEY (cus_id) REFERENCES customers(id) ON DELETE CASCADE
    )
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental');
    }
};
