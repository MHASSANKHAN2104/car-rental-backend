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
        DB::statement("
            CREATE TABLE vehicles (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                model VARCHAR(100) NOT NULL,
                brand VARCHAR(100) NOT NULL,
                year VARCHAR(150) UNIQUE NOT NULL,
                reg_number VARCHAR(20) NOT NULL,
                status bool,
                daily_rate VARCHAR(100),
                mileage VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Vehicles');
    }
};
