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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id('id_kendaraan');
            $table->string('nama_kendaraan', 255);
            $table->unsignedBigInteger('id_kategori');
            $table->string('plat_nomor', 20)->unique();
            $table->integer('kapasitas_muatan'); 
            $table->string('status_kendaraan')->default('tersedia');

            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategori')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
