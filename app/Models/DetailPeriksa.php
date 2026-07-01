<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeriksa extends Model
{
    // Sesuaikan nama tabel dengan database kamu, kalau jamak ganti jadi 'detail_periksas'
    protected $table = 'detail_periksa'; 

    // Laravel membutuhkan izin tertulis di sini agar bersedia memasukkan data ke MySQL
    protected $fillable = [
        'id_periksa',
        'id_obat',
        'jumlah', // <--- KUNCINYA DI SINI, BAL! JANGAN SAMPAI TERLEWAT
    ];

    public function periksa()
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}