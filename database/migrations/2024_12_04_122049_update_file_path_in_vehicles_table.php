<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFilePathInVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Dropping the existing filePath column
            $table->dropColumn('filePath');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            // Adding the new filePath column
            $table->string('filePath')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Dropping the new filePath column if rolling back
            $table->dropColumn('filePath');

            // Optionally, you can recreate the old column if necessary for rollback
            // $table->string('filePath')->nullable();
        });
    }
}
