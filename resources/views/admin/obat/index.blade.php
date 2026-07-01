<x-app-layout title="Data Obat">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Data Obat
        </h2>

        <a href="{{ route('obat.create') }}" class="btn bg-[#2d4499] hover:bg-[#1e2d6b] 
                  text-white border-none rounded-lg px-5">
            <i class="fas fa-plus text-xs"></i>
            Tambah Obat
        </a>
    </div>

    {{-- Alert Notifikasi Hasil Update Stok Manual --}}
    @if (session('message'))
        <div class="alert {{ session('type') == 'error' ? 'alert-error bg-red-500 text-white' : 'alert-success bg-emerald-500 text-white' }} mb-5 shadow-md rounded-xl p-4 border-none">
            <div class="flex items-center gap-3">
                <i class="fas {{ session('type') == 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle' }} text-lg"></i>
                <span class="font-semibold text-sm tracking-wide">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                {{-- Mengurangi text size ke text-xs/sm agar tabel tidak over-stretched --}}
                <table class="table table-sm w-full">

                    {{-- Table Head --}}
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-4 whitespace-nowrap">Nama Obat</th>
                            <th class="px-4 py-4 whitespace-nowrap">Kemasan</th>
                            <th class="px-4 py-4 whitespace-nowrap">Harga</th>
                            <th class="px-4 py-4 whitespace-nowrap">Stok</th>
                            <th class="px-4 py-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="text-xs text-slate-700">
                        @forelse($obats as $obat)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                            <td class="px-4 py-4 font-semibold text-slate-800 whitespace-nowrap">
                                {{ $obat->nama_obat }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-600">
                                    {{ $obat->kemasan ?? '-' }}
                                </span>
                            </td>

                            <td class="px-4 py-4 font-semibold text-slate-800 whitespace-nowrap">
                                Rp {{ number_format($obat->harga, 0, ',', '.') }}
                            </td>

                            {{-- INDIKATOR WARNA SISA STOK --}}
                            <td class="px-4 py-4 font-semibold whitespace-nowrap">
                                @if($obat->stok == 0)
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-red-100 text-red-600">
                                        Habis (0)
                                    </span>
                                @elseif($obat->stok < 10)
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-amber-100 text-amber-600">
                                        Menipis ({{ $obat->stok }})
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-emerald-100 text-emerald-600">
                                        {{ $obat->stok }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end items-center gap-3 whitespace-nowrap flex-nowrap">

                                    {{-- FORM KELOLA STOK MANUAL MINI (FIXED WRAPPING & PANAH DROPDOWN) --}}
                                    <form action="{{ route('obat.update-stok', $obat->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden shadow-sm bg-white h-8">
                                            <select name="aksi" class="appearance-none bg-slate-50 text-[11px] text-slate-700 font-bold px-3 h-full text-center cursor-pointer border-none focus:outline-none focus:bg-white transition-colors">
                                                <option value="tambah">Tambah</option>
                                                <option value="kurang">Kurang</option>
                                            </select>
                                            
                                            <input type="number" name="jumlah" value="1" min="1" 
                                                class="w-12 bg-white text-xs border-x border-slate-200 text-center font-bold text-slate-800 h-full focus:outline-none focus:ring-0" />
                                            
                                            <button type="submit" class="bg-[#2d4499] hover:bg-[#1e2d6b] text-white font-bold text-[11px] px-3 h-full flex items-center justify-center transition-colors">
                                                Go
                                            </button>
                                        </div>
                                    </form>

                                    {{-- Group Tombol Aksi --}}
                                    <div class="flex gap-1.5">
                                        {{-- Edit --}}
                                        <a href="{{ route('obat.edit', $obat->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 
                                                   bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('obat.destroy', $obat->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus obat ini?')" 
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition">
                                                <i class="fas fa-trash text-[10px]"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                Belum ada data obat
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-app-layout>