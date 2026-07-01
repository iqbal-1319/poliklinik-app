<x-app-layout title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('periksa-pasien.index') }}" class="inline-flex items-center justify-center w-9 h-9 
                  rounded-lg bg-slate-100 text-slate-500 
                  hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
    <div class="card-body p-8">

        {{-- LANGKAH 1: ALERT NOTIFIKASI ERROR / SUKSES DARI BACKEND --}}
        @if (session('message'))
            <div class="alert alert-{{ session('type') == 'error' ? 'error' : 'success' }} mb-5 shadow-sm rounded-xl text-white">
                <div class="flex items-center gap-2">
                    <i class="fas {{ session('type') == 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i>
                    <span>{{ session('message') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error mb-5 shadow-sm rounded-xl text-white">
                <div class="flex flex-col items-start gap-1">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="font-semibold">Terjadi kesalahan validasi:</span>
                    </div>
                    <ul class="list-disc pl-7 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        {{-- -------------------------------------------------------- --}}

        <form action="{{ route('periksa-pasien.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

            {{-- Pilih Obat --}}
            <div class="form-control mb-5">
                <label class="label pb-1">
                    <span class="text-sm font-semibold text-gray-700">Pilih Obat <span class="text-red-500">*</span></span>
                </label>
                <select id="select-text-obat" class="select select-bordered w-full rounded-lg border-2 px-4">
                    <option value="">-- Pilih Obat --</option>
                    @foreach ($obats as $obat)
                        <option value="{{ $obat->id }}"
                            data-nama="{{ $obat->nama_obat }}"
                            data-harga="{{ $obat->harga }}"
                            data-stok="{{ $obat->stok }}"> {{-- LANGKAH 2: DATA STOK UNTUK JAVASCRIPT --}}
                            {{ $obat->nama_obat }} - Rp{{ number_format($obat->harga) }} 
                            @if($obat->stok == 0)
                                [STOK HABIS]
                            @elseif($obat->stok <= 5)
                                [STOK MENIPIS: {{ $obat->stok }}]
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Obat Terpilih --}}
            <div class="form-control mb-5">
                <label class="label pb-1 ">
                    <span class="text-sm font-semibold text-gray-700">Obat Terpilih</span>
                </label>

                <ul id="obat-terpilih" class="flex flex-col gap-2 mb-2 min-h-[48px]"></ul>

                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                <input type="hidden" name="obat_json" id="obat_json">
            </div>

            {{-- Total Harga --}}
            <div class="form-control mb-5">
                <label class="label pb-1">
                    <span class="text-sm font-semibold text-gray-700">Total Harga</span>
                </label>
                <div class="input input-bordered w-full rounded-lg flex items-center bg-slate-50 text-slate-700 font-bold" id="total-harga">
                    Rp 0
                </div>
            </div>

            {{-- Catatan --}}
            <div class="form-control mb-8">
                <label class="label pb-1">
                    <span class="text-sm font-semibold text-gray-700">Catatan <span class="text-slate-400 font-normal">(Opsional)</span></span>
                </label>
                <textarea name="catatan" id="catatan" rows="4"
                    placeholder="Masukkan catatan..."
                    class="textarea textarea-bordered w-full border-2 px-4 py-2 rounded-lg resize-none">{{ old('catatan') }}</textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button type="submit"
                    class="btn bg-[#2d4499] hover:bg-[#1e2d6b] text-white border-none rounded-lg px-6">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>
                <a href="{{ route('periksa-pasien.index') }}"
                    class="btn btn-ghost bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg px-6">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    // Pastikan ID ini sama dengan ID di element <select> HTML-mu
    const selectObat = document.getElementById('select-text-obat');
    const listObat = document.getElementById('obat-terpilih');
    const inputBiaya = document.getElementById('biaya_periksa');
    const inputObatJson = document.getElementById('obat_json');
    const totalHargaEl = document.getElementById('total-harga');

    let daftarObat = [];
    const BIAYA_DOKTER = 150000; // Jasa periksa dasar dokter

    selectObat.addEventListener('change', () => {
        const selectedOption = selectObat.options[selectObat.selectedIndex];
        const id = selectedOption.value;
        const nama = selectedOption.dataset.nama;
        const harga = parseInt(selectedOption.dataset.harga || 0);
        
        // --- AMBIL DATA STOK DARI ATRIBUT HTML OPTION ---
        const stok = parseInt(selectedOption.dataset.stok || 0); 

        if (!id) return;

        // --- WALIDASI & NOTIFIKASI STOK HABIS DI FRONTEND (Poin b.1 Capstone) ---
        if (stok <= 0) {
            alert(`Gagal memilih! Stok obat "${nama}" sudah habis (0). Silakan hubungi admin untuk menambah stok.`);
            selectObat.selectedIndex = 0;
            return;
        }

        if (daftarObat.some(o => o.id == id)) {
            selectObat.selectedIndex = 0;
            return;
        }

        daftarObat.push({ id, nama, harga });
        renderObat();
        selectObat.selectedIndex = 0;
    });

    function renderObat() {
        listObat.innerHTML = '';
        let totalObat = 0;

        daftarObat.forEach((obat, index) => {
            totalObat += obat.harga;

            const item = document.createElement('li');
            item.className = 'flex items-center justify-between px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700';
            item.innerHTML = `
                <span>${obat.nama} — <span class="font-semibold">Rp ${obat.harga.toLocaleString('id-ID')}</span></span>
                <button type="button"
                    onclick="hapusObat(${index})"
                    class="btn btn-sm bg-red-500 hover:bg-red-600 text-white border-none rounded-lg px-3">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            listObat.appendChild(item);
        });

        // Simpan harga asli obat ke input agar Controller tinggal menjumlahkan Rp150.000 dengan benar
        inputBiaya.value = totalObat;

        // Tampilkan total akumulasi (Harga Obat + Jasa Dokter) ke UI
        const totalKeseluruhan = totalObat + BIAYA_DOKTER;
        totalHargaEl.textContent = `Rp ${totalKeseluruhan.toLocaleString('id-ID')} (Termasuk Jasa Dokter Rp 150.000)`;
        
        inputObatJson.value = JSON.stringify(daftarObat.map(o => o.id));
    }

    function hapusObat(index) {
        daftarObat.splice(index, 1);
        renderObat();
    }

    // Jalankan render pertama kali untuk mengeset angka awal Rp 150.000 (biaya periksa dasar)
    renderObat();
</script>
</x-app-layout>