@extends('layouts.admin')

@section('title', 'Import Data Kurban')
@section('page-title', 'Import Data Kurban')
@section('breadcrumb', 'Admin / Data Kurban / Import')

@section('header-actions')
    <a href="{{ route('admin.sacrifices.index') }}"
       class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
        <i class="ti ti-arrow-left"></i>
        Kembali
    </a>
@endsection

@section('content')
<div class="max-w-3xl space-y-6 pt-2">

    {{-- ── Download template ──────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ti ti-file-spreadsheet text-blue-600 text-lg"></i>
            </div>
            <div class="flex-1">
                <h2 class="font-semibold text-gray-800 mb-1">Unduh Template CSV</h2>
                <p class="text-sm text-gray-500 mb-4">
                    Gunakan template ini agar format kolom sesuai. File berisi 2 baris contoh yang bisa dihapus sebelum diisi data asli.
                </p>
                <a href="{{ route('admin.sacrifices.import.template') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                    <i class="ti ti-download"></i>
                    Unduh Template
                </a>
            </div>
        </div>
    </div>

    {{-- ── Petunjuk kolom ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="ti ti-info-circle text-gray-400"></i>
            Panduan Kolom
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-3 py-2 font-semibold text-gray-600 rounded-l-lg">Kolom</th>
                        <th class="px-3 py-2 font-semibold text-gray-600">Wajib</th>
                        <th class="px-3 py-2 font-semibold text-gray-600 rounded-r-lg">Keterangan &amp; Nilai yang Diterima</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach([
                        ['kode_referensi',       false, 'Kosongkan untuk auto-generate (NPC-YYYY-NNN)'],
                        ['jenis_kurban',          true,  'palestina / nusantara'],
                        ['nama_donatur',          true,  'Nama lengkap donatur'],
                        ['email_donatur',         false, 'Alamat email donatur'],
                        ['telepon_donatur',       false, 'Nomor telepon'],
                        ['jenis_hewan',           true,  'sapi / domba / unta'],
                        ['nominal',               false, 'Nominal yang dibayar donatur (angka saja, tanpa titik/koma)'],
                        ['kepemilikan',           true,  'full / collective'],
                        ['rasio_saham',           false, 'Jumlah saham (collective sapi maks 7, unta maks 10; full = 1)'],
                        ['tanggal_pembelian',     false, 'Format dd/mm/yyyy, misal: 22/06/2025'],
                        ['lokasi_pembelian',      false, 'Nama kota / lokasi pembelian hewan'],
                        ['lokasi_penyembelihan',  false, 'Lokasi penyembelihan'],
                        ['nama_penerima',         false, 'Nama penerima daging kurban'],
                        ['alamat_penerima',       false, 'Alamat lengkap penerima'],
                        ['jenis_penerima',        false, 'Misal: Yatim, Pengungsi, Dhuafa'],
                        ['catatan',               false, 'Catatan tambahan bebas'],
                    ] as [$col, $required, $desc])
                    <tr>
                        <td class="px-3 py-2 font-mono text-xs text-gray-700">{{ $col }}</td>
                        <td class="px-3 py-2">
                            @if($required)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">Wajib</span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">Opsional</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-gray-500">{{ $desc }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Form upload ─────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="ti ti-upload text-gray-400"></i>
            Unggah File CSV
        </h2>

        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.sacrifices.import.process') }}" enctype="multipart/form-data" id="import-form">
            @csrf

            {{-- Drop zone --}}
            <label for="file-input"
                   class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-[#1D9E75] hover:bg-green-50 transition-colors group"
                   id="drop-zone">
                <div class="flex flex-col items-center gap-2 text-gray-400 group-hover:text-[#1D9E75] transition-colors" id="drop-label">
                    <i class="ti ti-file-upload text-3xl"></i>
                    <span class="text-sm font-medium">Klik atau seret file CSV ke sini</span>
                    <span class="text-xs">Maksimal 5 MB · Format .csv</span>
                </div>
                <input id="file-input" name="file" type="file" accept=".csv,text/csv" class="hidden">
            </label>

            <div id="file-preview" class="hidden mt-3 flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                <i class="ti ti-file-text text-[#1D9E75] text-xl flex-shrink-0"></i>
                <div class="flex-1 min-w-0">
                    <p id="file-name" class="text-sm font-medium text-gray-800 truncate"></p>
                    <p id="file-size" class="text-xs text-gray-500"></p>
                </div>
                <button type="button" onclick="clearFile()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="ti ti-x"></i>
                </button>
            </div>

            <div class="mt-5 flex items-center gap-3">
                <button type="submit" id="submit-btn"
                        class="inline-flex items-center gap-2 bg-[#1D9E75] hover:bg-[#157a5a] disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors"
                        disabled>
                    <i class="ti ti-file-import"></i>
                    Import Data
                </button>
                <span class="text-xs text-gray-400">Status semua data yang diimport akan dimulai dari <strong>Menunggu</strong>.</span>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
const input    = document.getElementById('file-input');
const dropZone = document.getElementById('drop-zone');
const preview  = document.getElementById('file-preview');
const label    = document.getElementById('drop-label');
const submitBtn = document.getElementById('submit-btn');

function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

function showFile(file) {
    document.getElementById('file-name').textContent = file.name;
    document.getElementById('file-size').textContent = formatBytes(file.size);
    preview.classList.remove('hidden');
    label.classList.add('hidden');
    submitBtn.disabled = false;
}

function clearFile() {
    input.value = '';
    preview.classList.add('hidden');
    label.classList.remove('hidden');
    submitBtn.disabled = true;
}

input.addEventListener('change', () => {
    if (input.files[0]) showFile(input.files[0]);
});

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-[#1D9E75]', 'bg-green-50'); });
dropZone.addEventListener('dragleave', () => { dropZone.classList.remove('border-[#1D9E75]', 'bg-green-50'); });
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-[#1D9E75]', 'bg-green-50');
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        showFile(file);
    }
});

document.getElementById('import-form').addEventListener('submit', () => {
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ti ti-loader animate-spin"></i> Memproses...';
});
</script>
@endpush
