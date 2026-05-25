@if($sacrifice->hasCertificate())
<div class="space-y-4">
    {{-- Certificate ready --}}
    <div class="bg-gradient-to-br from-[#1D9E75] to-[#157a5a] rounded-xl p-6 text-white text-center">
        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ti ti-certificate text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold mb-1">Sertifikat Kurban Tersedia</h3>
        <p class="text-white/80 text-sm mb-1">
            Sertifikat diterbitkan pada {{ $sacrifice->certificate_generated_at->translatedFormat('d F Y') }}
        </p>
        <p class="text-white/60 text-xs mb-5">
            Dokumen resmi pelaksanaan kurban atas nama {{ $sacrifice->donor_name }}
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('sacrifice.certificate.download', $sacrifice->reference_code) }}"
               class="inline-flex items-center justify-center gap-2 bg-white text-[#1D9E75] font-bold px-6 py-3 rounded-xl hover:bg-white/90 transition-colors shadow-lg">
                <i class="ti ti-download"></i>
                Unduh PDF
            </a>
            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 text-white font-semibold px-6 py-3 rounded-xl transition-colors border border-white/30">
                <i class="ti ti-printer"></i>
                Cetak
            </button>
        </div>
    </div>

    {{-- Certificate details --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 text-sm mb-4">Ringkasan Sertifikat</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-500">Kode Referensi</p>
                <p class="font-mono font-semibold text-gray-800">{{ $sacrifice->reference_code }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Nama Donatur</p>
                <p class="font-semibold text-gray-800">{{ $sacrifice->donor_name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Jenis Hewan</p>
                <p class="font-semibold text-gray-800">{{ $sacrifice->getAnimalTypeLabel() }} — {{ $sacrifice->getSacrificeTypeLabel() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Penerima</p>
                <p class="font-semibold text-gray-800">{{ $sacrifice->beneficiary_name ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@else
<div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="ti ti-certificate-off text-gray-400 text-2xl"></i>
    </div>
    <h3 class="font-semibold text-gray-700 mb-1">Sertifikat Belum Tersedia</h3>
    <p class="text-gray-400 text-sm max-w-xs mx-auto">
        Sertifikat akan tersedia setelah seluruh tahapan pelaksanaan kurban selesai
    </p>

    <div class="mt-6 bg-gray-50 rounded-lg p-4 text-left max-w-xs mx-auto">
        <p class="text-xs font-medium text-gray-600 mb-2">Status saat ini:</p>
        @foreach($sacrifice->getProgress() as $stage)
        <div class="flex items-center gap-2 py-1">
            <i class="ti {{ $stage['status'] === 'completed' ? 'ti-circle-check text-green-500' : 'ti-circle-dashed text-gray-300' }} text-sm"></i>
            <span class="text-xs {{ $stage['status'] === 'completed' ? 'text-gray-700' : 'text-gray-400' }}">{{ $stage['label'] }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif
