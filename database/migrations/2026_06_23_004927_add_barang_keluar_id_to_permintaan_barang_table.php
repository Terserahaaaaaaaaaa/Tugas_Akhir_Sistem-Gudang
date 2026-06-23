<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permintaan_barang', function (Blueprint $table) {

            $table->foreignId('barang_keluar_id')
                ->nullable()
                ->after('id')
                ->constrained('barang_keluar')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('permintaan_barang', function (Blueprint $table) {

            $table->dropForeign(['barang_keluar_id']);
            $table->dropColumn('barang_keluar_id');

        });
    }
};