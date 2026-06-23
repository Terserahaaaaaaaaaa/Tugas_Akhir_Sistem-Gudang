<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokOpname extends Model
{
    use HasFactory;

    protected $table = 'stok_opname';

    protected $fillable = [
        'tanggal_opname',
        'user_id',
        'keterangan'
    ];

    //relasi
    public function user()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function detail()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            StokOpnameDetail::class,
            'stok_opname_id'
        );
    }
}