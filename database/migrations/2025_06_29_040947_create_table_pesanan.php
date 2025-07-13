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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelanggan_id');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->onDelete('cascade');
            $table->unsignedBigInteger('id_kategori'); 
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade'); 
            $table->unsignedBigInteger('petugas_id')->nullable();
            $table->foreign('petugas_id')->references('id')->on('petugass')->onDelete('set null');
            $table->unsignedBigInteger('id_kendaraan')->nullable();
            $table->foreign('id_kendaraan')->references('id_kendaraan')->on('kendaraans')->onDelete('set null');
            $table->dateTime('tanggal_jemput')->nullable(); 
            $table->text('alamat_jemput');
            $table->double('lat_jemput', 10, 6);
            $table->double('lng_jemput', 10, 6);
            $table->text('alamat_tujuan');
            $table->double('lat_tujuan', 10, 6);
            $table->double('lng_tujuan', 10, 6);
            $table->decimal('jarak_km', 8, 2); 
            $table->unsignedBigInteger('biaya'); 
            $table->enum('status', [
                'pending',
                'disetujui',
                'dalam_perjalanan',
                'selesai'
            ])->default('pending');
            $table->string('foto_bukti_selesai')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
