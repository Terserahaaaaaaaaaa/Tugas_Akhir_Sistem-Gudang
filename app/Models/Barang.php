<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kategori_barang_id',
        'kode_barang',
        'nama_barang',
        'satuan',
        'stok',
        'stok_minimum',
        'harga_terakhir',
        'foto'
    ];

    //relasi
    public function kategori()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            KategoriBarang::class,
            'kategori_barang_id'
        );
    }

    public function detailBarangMasuk()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            BarangMasukDetail::class
        );
    }

    public function detailBarangKeluar()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            BarangKeluarDetail::class
        );
    }

    public function riwayatHarga()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            RiwayatHarga::class
        );
    }
}