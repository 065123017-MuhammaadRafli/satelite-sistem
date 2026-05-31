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
    Schema::table('ground_stations', function (Blueprint $table) {
        // Menambahkan kolom altitude setelah longitude
        $table->decimal('altitude', 8, 2)->nullable()->after('longitude');
    });
}

public function down()
{
    Schema::table('ground_stations', function (Blueprint $table) {
        $table->dropColumn('altitude');
    });
}
};
