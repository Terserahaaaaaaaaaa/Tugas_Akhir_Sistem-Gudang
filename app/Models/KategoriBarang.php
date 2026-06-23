<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;

    protected $table = 'kategori_barang';

    protected $fillable = [
        'no_akun',
        'nama_akun'
    ];

    public function barang()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(Barang::class);
    }
}
