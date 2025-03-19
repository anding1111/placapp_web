<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plates', function (Blueprint $table) {
            $table->id();
            $table->string('plate_name', 12);
            $table->string('plate_desc', 120)->default('');
            $table->dateTime('plate_entry_date')->useCurrent();
            $table->dateTime('plate_exit_date')->nullable();
            $table->boolean('plate_enable')->default(true);
            $table->integer('plate_level')->default(3);
            $table->string('plate_location', 50)->default('');
            $table->string('plate_detail', 200)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plates');
    }
};
