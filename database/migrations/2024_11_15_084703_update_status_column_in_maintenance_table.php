<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnInMaintenanceTable extends Migration
{
    public function up()
    {
        Schema::table('maintenance', function (Blueprint $table) {
            // Modify the status column to use a string type or an ENUM type
            $table->string('status', 20)->default('pending')->change();
            // OR use the ENUM type if you want restricted values
            // $table->enum('status', ['pending', 'completed', 'in-progress'])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('maintenance', function (Blueprint $table) {
            // Revert the changes in case the migration is rolled back
            $table->string('status')->nullable()->change();
        });
    }
}
