<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermintaanBarang extends Model
{
    use HasFactory;

    protected $table = 'permintaan_barang';

    protected $fillable = [
        'barang_keluar_id',
        'tanggal_permintaan',
        'divisi',
        'keterangan',
        'status_permintaan'
    ];

    public function detail()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            PermintaanBarangDetail::class,
            'permintaan_barang_id'
        );
    }

    public function barangKeluar()
{
    return $this->belongsTo(
        BarangKeluar::class,
        'barang_keluar_id'
    );
}
}