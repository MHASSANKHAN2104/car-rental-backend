<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            // First, check if the columns exist before trying to drop them
            // Drop the 'brand' and 'status' columns from the 'vehicles' table if they exist
            DB::statement("ALTER TABLE vehicles DROP COLUMN IF EXISTS brand, DROP COLUMN IF EXISTS status");

            // Add the new foreign key columns
            DB::statement("
                ALTER TABLE vehicles
                ADD COLUMN brand_id INT UNSIGNED,
                ADD COLUMN status_id INT UNSIGNED
            ");

            // Add foreign key constraints
            DB::statement("
                ALTER TABLE vehicles
                ADD CONSTRAINT fk_vehicle_brand FOREIGN KEY (brand_id) REFERENCES brands(id),
                ADD CONSTRAINT fk_vehicle_status FOREIGN KEY (status_id) REFERENCES statuses(id)
            ");
        } catch (\Exception $e) {
            // Handle the error if columns are missing or if the drop fails
            \Log::error('Migration error: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            // Drop foreign key constraints first
            DB::statement("ALTER TABLE vehicles DROP FOREIGN KEY fk_vehicle_brand");
            DB::statement("ALTER TABLE vehicles DROP FOREIGN KEY fk_vehicle_status");

            // Remove the foreign key columns
            DB::statement("ALTER TABLE vehicles DROP COLUMN brand_id, DROP COLUMN status_id");

            // Re-add the original 'brand' and 'status' columns
            DB::statement("
                ALTER TABLE vehicles
                ADD COLUMN brand VARCHAR(100),
                ADD COLUMN status VARCHAR(100)
            ");
        } catch (\Exception $e) {
            \Log::error('Rollback error: ' . $e->getMessage());
        }
    }

};
