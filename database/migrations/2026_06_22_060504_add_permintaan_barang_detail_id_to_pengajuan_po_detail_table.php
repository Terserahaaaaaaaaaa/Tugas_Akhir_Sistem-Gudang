<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_po_detail', function (Blueprint $table) {

            $table->foreignId('permintaan_barang_detail_id')
                ->nullable()
                ->after('pengajuan_po_id')
                ->constrained('permintaan_barang_detail')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_po_detail', function (Blueprint $table) {

            $table->dropForeign([
                'permintaan_barang_detail_id'
            ]);

            $table->dropColumn(
                'permintaan_barang_detail_id'
            );
        });
    }
};