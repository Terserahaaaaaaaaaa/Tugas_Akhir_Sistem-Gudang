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
    Schema::table('barang_keluar', function (Blueprint $table) {

        $table->dropForeign(['permintaan_barang_id']);
        $table->dropColumn('permintaan_barang_id');

    });
}

public function down(): void
{
    Schema::table('barang_keluar', function (Blueprint $table) {

        $table->foreignId('permintaan_barang_id')
              ->nullable()
              ->constrained('permintaan_barang')
              ->nullOnDelete();

    });
}
};
