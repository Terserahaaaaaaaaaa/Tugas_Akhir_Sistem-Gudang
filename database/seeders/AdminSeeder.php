<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        //pake firstOrCreate supaya jika php migrate seeder dijalankan lagi tidak error email duplikat
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Gudang',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status_akun' => 'disetujui',
            ]
        );
    }
}