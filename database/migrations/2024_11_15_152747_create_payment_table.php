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
            CREATE TABLE payment (
                payment_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                rental_id INT UNSIGNED,
                amount DECIMAL(10, 2) NOT NULL,
                payment_date DATETIME NOT NULL,
                payment_method ENUM('CASH', 'CARD', 'BANK_TRANSFER') DEFAULT 'CASH',
                status ENUM('PENDING', 'COMPLETED', 'REFUNDED') DEFAULT 'PENDING',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (rental_id) REFERENCES rental(rental_id) ON DELETE CASCADE
            )
        ");
    }

    public function down()
    {
        DB::statement("DROP TABLE IF EXISTS payment");
    }
};
