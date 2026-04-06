<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment).
     * Tambahkan kolom khusus Poliklinik di sini.
     */
    protected $fillable = [
        'name',      // Laravel default
        'nama',      // Sesuai modul kamu (jika ingin pakai nama terpisah)
        'alamat',
        'no_ktp',
        'no_hp',
        'no_rm',
        'role',
        'id_poli',
        'email',
        'password',
    ];

    /**
     * Kolom yang akan disembunyikan saat data dikirim (misal lewat API).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Pengaturan tipe data kolom.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** * --- RELASI ELOQUENT ---
     */

    // Menghubungkan Dokter ke Polinya
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    // Menghubungkan Dokter ke Jadwal Periksanya
    public function jadwalPeriksa()
    {
        return $this->hasMany(JadwalPeriksa::class, 'id_dokter');
    }

    // Jika User ini adalah Pasien, dia punya banyak DaftarPoli
    public function daftarPolis()
    {
        return $this->hasMany(DaftarPoli::class, 'id_pasien');
    }
}