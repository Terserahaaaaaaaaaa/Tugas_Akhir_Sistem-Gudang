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
        Schema::create('pengajuan_po_detail', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pengajuan_po_id')
                  ->constrained('pengajuan_po')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('barang_id')
                  ->constrained('barang')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->integer('qty');

            $table->decimal('harga_estimasi', 15, 2);

            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_po_detail');
    }
};
