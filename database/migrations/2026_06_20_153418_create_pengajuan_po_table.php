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
        Schema::create('pengajuan_po', function (Blueprint $table) {
            $table->id();

            $table->date('tanggal_po');
            $table->enum('sumber_po', ['permintaan_barang', 'stok_minimum']);
            $table->string('kontak_pembelian')->nullable();
            $table->enum('metode_pembelian', ['whatsapp', 'online', 'beli_langsung'])->nullable();
            $table->enum('status_po', ['pending', 'disetujui', 'disetujui_sebagian', 'ditolak'])->default('pending');

            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_po');
    }
};
