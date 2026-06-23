<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_keluar_detail', function (Blueprint $table) {

            $table->integer('qty_keluar')
                ->default(0)
                ->after('qty');

            $table->integer('qty_kurang')
                ->default(0)
                ->after('qty_keluar');

        });
    }

    public function down(): void
    {
        Schema::table('barang_keluar_detail', function (Blueprint $table) {

            $table->dropColumn([
                'qty_keluar',
                'qty_kurang'
            ]);

        });
    }
};