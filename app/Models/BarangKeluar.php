<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';

    protected $fillable = [
        'tanggal_keluar',
        'divisi_tujuan',
        'keterangan'
    ];

    //relasi

    public function detail()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            BarangKeluarDetail::class,
            'barang_keluar_id'
        );
    }

    public function permintaanBarang()
{
    return $this->hasOne(
        PermintaanBarang::class,
        'barang_keluar_id'
    );
}
}