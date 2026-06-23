<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatHarga extends Model
{
    use HasFactory;

    protected $table = 'riwayat_harga';

    protected $fillable = [
        'barang_id',
        'barang_masuk_detail_id',
        'harga_lama',
        'harga_baru',
        'tanggal_perubahan'
    ];

    public function barang()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            Barang::class,
            'barang_id'
        );
    }

    public function barangMasukDetail()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            BarangMasukDetail::class,
            'barang_masuk_detail_id'
        );
    }
}