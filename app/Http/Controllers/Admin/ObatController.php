<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::all();
        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'kemasan' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|integer|min:0', // <-- Tambahkan validasi stok
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
            'stok' => $request->stok, // <-- Simpan nilai stok ke database
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat Berhasil dibuat')
            ->with('type', 'success');
    }

    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);
        return view('admin.obat.edit')->with([
            'obat' => $obat
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'kemasan' => 'nullable|string',
            'harga' => 'required|integer',
            'stok' => 'required|integer|min:0', // <-- Tambahkan validasi stok di form edit
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
            'stok' => $request->stok, // <-- Perbarui nilai stok di database
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil di edit')
            ->with('type', 'success');
    }

    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat Berhasil di Hapus')
            ->with('type', 'success');
    }

    public function updateStok(Request $request, $id)
{
    $request->validate([
        'aksi' => 'required|in:tambah,kurang',
        'jumlah' => 'required|integer|min:1',
    ]);

    $obat = Obat::findOrFail($id);

    if ($request->aksi === 'tambah') {
        $obat->increment('stok', $request->jumlah);
        $pesan = "Stok obat '{$obat->nama_obat}' berhasil ditambah sebanyak {$request->jumlah}!";
        
        return redirect()->route('obat.index')
            ->with('message', $pesan)
            ->with('type', 'success');
    } else {
        if ($obat->stok < $request->jumlah) {
            return redirect()->back()
                ->with('message', 'Gagal! Jumlah pengurangan melebihi stok yang tersedia.')
                ->with('type', 'error'); // <-- Kita set typenya jadi 'error' agar komponen DaisyUI berubah merah
        }
        
        $obat->decrement('stok', $request->jumlah);
        $pesan = "Stok obat '{$obat->nama_obat}' berhasil dikurangi sebanyak {$request->jumlah}!";
        
        return redirect()->route('obat.index')
            ->with('message', $pesan)
            ->with('type', 'success');
    }
}
}