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
        Schema::create('stok_opname_detail', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stok_opname_id')
                  ->constrained('stok_opname')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('barang_id')
                  ->constrained('barang')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->integer('stok_sistem');

            $table->integer('stok_fisik');

            $table->integer('selisih');

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_opname_detail');
    }
};
