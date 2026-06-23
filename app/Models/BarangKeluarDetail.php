<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluarDetail extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar_detail';

    protected $fillable = [
        'barang_keluar_id',
        'barang_id',
        'qty',
        'qty_keluar',
        'qty_kurang'
    ];

    //relasi
    public function barangKeluar()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            BarangKeluar::class,
            'barang_keluar_id'
        );
    }

    public function barang()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            Barang::class,
            'barang_id'
        );
    }
}