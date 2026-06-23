<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {

            $table->foreignId('dilakukan_oleh')
                ->after('pengajuan_po_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {

            $table->dropForeign(['dilakukan_oleh']);
            $table->dropColumn('dilakukan_oleh');

        });
    }
};