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
    Schema::table('satellites', function (Blueprint $table) {
        // Menambahkan kolom tepat setelah kolom 'description'
        $table->string('dynamic_api_url')->nullable()->after('description');
    });
}

public function down()
{
    Schema::table('satellites', function (Blueprint $table) {
        $table->dropColumn('dynamic_api_url');
    });
}

};
