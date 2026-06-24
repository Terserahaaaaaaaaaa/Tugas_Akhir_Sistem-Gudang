<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE permintaan_barang
            MODIFY status_permintaan ENUM(
                'baru',
                'diajukan_po',
                'terpenuhi',
                'tidak_terpenuhi'
            ) NOT NULL DEFAULT 'baru'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE permintaan_barang
            MODIFY status_permintaan ENUM(
                'baru',
                'diproses',
                'terpenuhi',
                'tidak_terpenuhi'
            ) NOT NULL DEFAULT 'baru'
        ");
    }
};