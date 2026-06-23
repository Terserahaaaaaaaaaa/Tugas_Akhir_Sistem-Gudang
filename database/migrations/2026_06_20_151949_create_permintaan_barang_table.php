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
        Schema::create('permintaan_barang', function (Blueprint $table) {
            $table->id();
            
            $table->date('tanggal_permintaan');
            $table->string('divisi');
            $table->text('keterangan')->nullable();
            $table->enum('status_permintaan', ['baru', 'diproses', 'terpenuhi', 'tidak_terpenuhi', 'diajukan_po'])->default('baru');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_barang');
    }
};
