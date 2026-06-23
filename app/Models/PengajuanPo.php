<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanPo extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_po';

    protected $fillable = [
        'tanggal_po',
        'sumber_po',
        'kontak_pembelian',
        'metode_pembelian',
        'status_po',
        'diajukan_oleh',
        'disetujui_oleh'
    ];

    //relasi
    public function detail()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(PengajuanPoDetail::class,'pengajuan_po_id');
    }

    public function diajukan()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            User::class,
            'diajukan_oleh'
        );
    }

    public function approver()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(User::class,'disetujui_oleh');
    }

    public function barangMasuk()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            BarangMasuk::class,
            'pengajuan_po_id'
        );
    }

}