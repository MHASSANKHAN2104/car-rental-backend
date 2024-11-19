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
    Schema::table('vehicles', function (Blueprint $table) {
        $table->string('filePath')->nullable(); // Define your column type here
    });
}

public function down()
{
    Schema::table('vehicles', function (Blueprint $table) {
        $table->dropColumn('filePath');
    });
}
};
