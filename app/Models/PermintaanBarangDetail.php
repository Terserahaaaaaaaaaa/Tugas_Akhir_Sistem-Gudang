<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermintaanBarangDetail extends Model
{
    use HasFactory;

    protected $table = 'permintaan_barang_detail';

    protected $fillable = [
        'permintaan_barang_id',
        'barang_id',
        'qty',
        'size',
        'keterangan',
        'status_item'
    ];

    //relasi
    public function permintaanBarang()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(PermintaanBarang::class,'permintaan_barang_id');
    }

    public function barang()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(Barang::class,'barang_id');
    }

    public function pengajuanPoDetail()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            PengajuanPODetail::class,
            'permintaan_barang_detail_id'
        );
    }
}