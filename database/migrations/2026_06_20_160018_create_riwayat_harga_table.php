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
        Schema::create('riwayat_harga', function (Blueprint $table) {
            $table->id();

            $table->foreignId('barang_id')
                  ->constrained('barang')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('barang_masuk_detail_id')
                  ->constrained('barang_masuk_detail')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->decimal('harga_lama', 15, 2);

            $table->decimal('harga_baru', 15, 2);

            $table->date('tanggal_perubahan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_harga');
    }
};
