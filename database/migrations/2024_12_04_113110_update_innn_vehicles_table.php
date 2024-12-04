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
           // Remove the old file_path column
           if (Schema::hasColumn('vehicles', 'file_path')) {
               $table->dropColumn('file_path');
           }

           // Add a new file_path column
           $table->string('filePath')->nullable();
       });
   }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
