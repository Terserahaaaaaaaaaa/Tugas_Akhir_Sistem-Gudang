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
        Schema::table('permintaan_barang_detail', function (Blueprint $table) {
            $table->enum('status_item', ['tersedia', 'tidak_tersedia'])->default('tidak_tersedia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_barang_detail', function (Blueprint $table) {
            //
        });
    }
};
