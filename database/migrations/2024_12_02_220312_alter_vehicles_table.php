
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
            ALTER TABLE vehicles
            ADD COLUMN brand_id INT UNSIGNED,
            ADD COLUMN status_id INT UNSIGNED,

            ADD CONSTRAINT fk_vehicle_brand FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL,
            ADD CONSTRAINT fk_vehicle_status FOREIGN KEY (status_id) REFERENCES statuses(id) ON DELETE SET NULL;
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE vehicles
            DROP COLUMN brand_id,
            DROP COLUMN status_id,
            DROP COLUMN file_path,
            DROP FOREIGN KEY fk_vehicle_brand,
            DROP FOREIGN KEY fk_vehicle_status;
        ");
    }

    };

