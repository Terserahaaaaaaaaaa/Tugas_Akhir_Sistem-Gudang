<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'tanggal_masuk',
        'pengajuan_po_id',
        'dilakukan_oleh',
        'keterangan'
    ];

    //relasi
    public function pengajuanPo()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            PengajuanPo::class,
            'pengajuan_po_id'
        );
    }

    public function detail()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            BarangMasukDetail::class,
            'barang_masuk_id'
        );
    }

    public function user()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            User::class,
            'dilakukan_oleh'
        );
    }
}