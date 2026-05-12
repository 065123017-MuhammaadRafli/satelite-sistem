<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satellites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country');
            $table->date('launch_date');
            $table->enum('orbit_type', ['LEO', 'MEO', 'GEO']);
            $table->char('tle_line1', 69)->nullable();
            $table->char('tle_line2', 69)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('ground_station_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satellites');
    }
};