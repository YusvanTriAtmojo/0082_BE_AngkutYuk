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
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropColumn('status_kendaraan');
        });

        Schema::table('kendaraans', function (Blueprint $table) {
            $table->enum('status_kendaraan', ['tersedia', 'terpakai', 'rusak'])->default('tersedia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropColumn('status_kendaraan');
        });

        Schema::table('kendaraans', function (Blueprint $table) {
            $table->string('status_kendaraan')->default('tersedia');
        });
    }
};
