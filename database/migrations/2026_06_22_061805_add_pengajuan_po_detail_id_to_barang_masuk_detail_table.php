<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_masuk_detail', function (Blueprint $table) {

            $table->foreignId('pengajuan_po_detail_id')
                ->nullable()
                ->after('barang_masuk_id')
                ->constrained('pengajuan_po_detail')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('barang_masuk_detail', function (Blueprint $table) {

            $table->dropForeign([
                'pengajuan_po_detail_id'
            ]);

            $table->dropColumn(
                'pengajuan_po_detail_id'
            );

        });
    }
};