<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antri')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required',
            'obat_json' => 'required',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        // --- FIX FORMAT JSON YANG TERBUNGKUS KUTIP GANDA EKSTRA ---
        $obatRaw = $request->obat_json;
        if (is_string($obatRaw) && (str_starts_with($obatRaw, '"') || str_starts_with($obatRaw, "'"))) {
            $obatRaw = trim($obatRaw, '"\'');
        }

        $obatIds = is_array($obatRaw) ? $obatRaw : json_decode($obatRaw, true);

        if (!is_array($obatIds)) {
            $obatIds = [];
        }

        // --- 1. VALIDASI STOK SEBELUM MENYIMPAN APAPUN ---
        foreach ($obatIds as $idObat) {
            $obat = Obat::find($idObat);
            $jumlahDibutuhkan = 1; 

            if (!$obat || $obat->stok < $jumlahDibutuhkan) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Stok obat '{$obat->nama_obat}' tidak mencukupi atau habis!");
            }
        }

        // --- 2. JIKA SEMUA STOK AMAN, JALANKAN TRANSACTION ---
        try {
            \DB::transaction(function () use ($request, $obatIds) {
                
                // Simpan data periksa
                $periksa = Periksa::create([
                    'id_daftar_poli' => $request->id_daftar_poli,
                    'tgl_periksa' => now(),
                    'catatan' => $request->catatan,
                    'biaya_periksa' => $request->biaya_periksa + 150000,
                ]);

                // --- 3. SIMPAN DETAIL DAN KURANGI STOK SECARA OTOMATIS ---
                foreach ($obatIds as $idObat) {
                    $jumlahDiambil = 1;

                    // Simpan ke tabel detail_periksa beserta jumlahnya
                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $idObat,
                        'jumlah' => $jumlahDiambil,
                    ]);

                    // Potong stok obat di database secara otomatis
                    $obat = Obat::find($idObat);
                    $obat->decrement('stok', $jumlahDiambil); 
                }
            });

            return redirect()->route('periksa-pasien.index')->with('success', 'Data periksa berhasil disimpan.');

        } catch (\Exception $e) {
            // Hapus dd($e->getMessage()); dan ganti jadi redirect normal ini:
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}