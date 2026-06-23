<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_po', function (Blueprint $table) {

            $table->foreignId('diajukan_oleh')
                ->after('status_po')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_po', function (Blueprint $table) {

            $table->dropForeign(['diajukan_oleh']);
            $table->dropColumn('diajukan_oleh');

        });
    }
};