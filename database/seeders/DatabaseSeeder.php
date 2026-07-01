<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'nama' => 'Iqbal Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'alamat' => 'Karawang',
            'no_ktp' => '1234567890',
            'no_hp' => '08123456789',
            'no_rm' => '999999',
        ]);

        // 2. Akun Dokter
        $dokter = User::create([
            'nama' => 'Dokter Iqbal',
            'email' => 'dokter@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
            'alamat' => 'Karawang',
            'no_ktp' => '1234567891',
            'no_hp' => '08123456788',
            'no_rm' => '888888',
        ]);

        // 3. Akun Pasien (Sekarang dilengkapi biar tidak error field kosong)
        $pasien = User::create([
            'nama' => 'Pasien Budi',
            'email' => 'pasien@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'pasien', // <-- Sudah sesuai role di AuthController
            'alamat' => 'Jl. Merdeka No. 12',
            'no_ktp' => '3215011234560001',
            'no_hp' => '085712345678',
            'no_rm' => date('Ym') . '-001', // Format RM otomatis: TahunBulan-001
        ]);

      
    }
}