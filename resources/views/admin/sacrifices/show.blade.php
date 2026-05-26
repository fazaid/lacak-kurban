@extends('layouts.admin')

@section('title', 'Detail ' . $sacrifice->reference_code)
@section('page-title', $sacrifice->reference_code)
@section('breadcrumb', 'Admin / Data Kurban / ' . $sacrifice->reference_code)

@section('header-actions')
    @if(auth()->user()->canWrite())
    <a href="{{ route('admin.sacrifices.edit', $sacrifice) }}"
       class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 text-sm font-medium px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
        <i class="ti ti-edit"></i> Edit
    </a>
    <a href="{{ route('admin.sacrifices.progress.edit', $sacrifice) }}"
       class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-3 py-2 rounded-lg transition-colors">
        <i class="ti ti-timeline"></i> Update Progress
    </a>
    @endif
@endsection

@section('content')
<div class="space-y-5 pt-2">
    {{-- Status bar --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold px-3 py-1.5 rounded-full {{ $sacrifice->getStatusBadgeClass() }}">
                {{ $sacrifice->getStatusLabel() }}
            </span>
        </div>
        <div class="flex-1 flex items-center gap-3">
            <div class="flex-1 max-w-xs bg-gray-100 rounded-full h-2">
                <div class="bg-[#1D9E75] h-full rounded-full transition-all" style="width: {{ $sacrifice->getProgressPercentage() }}%"></div>
            </div>
            <span class="text-sm font-bold text-[#1D9E75]">{{ $sacrifice->getProgressPercentage() }}%</span>
        </div>
        <div class="text-xs text-gray-500">
            Didaftarkan: {{ $sacrifice->created_at->format('d M Y') }}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Left column --}}
        <div class="space-y-4">
            {{-- Donor --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <i class="ti ti-user-circle text-[#1D9E75]"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Donatur</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div><p class="text-xs text-gray-500">Nama</p><p class="font-semibold text-sm">{{ $sacrifice->donor_name }}</p></div>
                    @if($sacrifice->donor_email)<div><p class="text-xs text-gray-500">Email</p><p class="text-sm">{{ $sacrifice->donor_email }}</p></div>@endif
                    @if($sacrifice->donor_phone)<div><p class="text-xs text-gray-500">Telepon</p><p class="text-sm">{{ $sacrifice->donor_phone }}</p></div>@endif
                </div>
            </div>

            {{-- Animal --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <i class="ti ti-paw text-[#1D9E75]"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Hewan Kurban</h3>
                </div>
                <div class="p-4 grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-gray-500">Program</p>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full mt-0.5
                            {{ $sacrifice->sacrifice_type === 'palestina' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $sacrifice->getSacrificeTypeLabel() }}
                        </span>
                    </div>
                    <div><p class="text-xs text-gray-500">Jenis Hewan</p><p class="font-semibold text-sm">{{ $sacrifice->getAnimalTypeLabel() }}</p></div>
                    @if($sacrifice->animal_price)
                    <div><p class="text-xs text-gray-500">Nominal</p><p class="font-semibold text-sm">Rp {{ number_format($sacrifice->animal_price, 0, ',', '.') }}</p></div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500">Jenis Berbagi</p>
                        <p class="font-semibold text-sm">{{ $sacrifice->getShareLabel() }}</p>
                    </div>
                    @if($sacrifice->purchase_date)<div><p class="text-xs text-gray-500">Tgl Beli</p><p class="text-sm">{{ $sacrifice->purchase_date->format('d/m/Y') }}</p></div>@endif
                    @if($sacrifice->purchase_location)<div><p class="text-xs text-gray-500">Lokasi Beli</p><p class="text-sm">{{ $sacrifice->purchase_location }}</p></div>@endif
                    @if($sacrifice->slaughter_location)<div class="col-span-2"><p class="text-xs text-gray-500">Lokasi Sembelih</p><p class="text-sm">{{ $sacrifice->slaughter_location }}</p></div>@endif
                </div>
            </div>

            {{-- Beneficiary --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <i class="ti ti-heart-handshake text-[#1D9E75]"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Penerima Manfaat</h3>
                </div>
                <div class="p-4 space-y-3">
                    @if($sacrifice->beneficiary_name)<div><p class="text-xs text-gray-500">Nama</p><p class="font-semibold text-sm">{{ $sacrifice->beneficiary_name }}</p></div>@endif
                    @if($sacrifice->beneficiary_type)<div><p class="text-xs text-gray-500">Jenis</p><p class="text-sm">{{ $sacrifice->beneficiary_type }}</p></div>@endif
                    @if($sacrifice->beneficiary_address)<div><p class="text-xs text-gray-500">Alamat</p><p class="text-sm">{{ $sacrifice->beneficiary_address }}</p></div>@endif
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="space-y-4">
            {{-- Progress stages --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ti ti-timeline text-[#1D9E75]"></i>
                        <h3 class="font-semibold text-gray-800 text-sm">Status Progress</h3>
                    </div>
                    @if(auth()->user()->canWrite())
                    <a href="{{ route('admin.sacrifices.progress.edit', $sacrifice) }}"
                       class="text-xs text-[#1D9E75] font-medium hover:underline">Edit</a>
                    @endif
                </div>
                <div class="p-4 space-y-3">
                    @foreach($sacrifice->getProgress() as $stage)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center
                                {{ $stage['status'] === 'completed' ? 'bg-[#1D9E75] text-white' : 'bg-gray-100 text-gray-400' }}">
                                <i class="ti {{ $stage['icon'] }} text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-700">{{ $stage['label'] }}</p>
                                @if($stage['status'] === 'completed' && $stage['date'])
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($stage['date'])->format('d/m/Y') }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full
                            {{ $stage['status'] === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $stage['status'] === 'completed' ? 'Selesai' : 'Menunggu' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Link Portal Donatur --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <i class="ti ti-link text-[#1D9E75]"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Link Portal Donatur</h3>
                </div>
                <div class="p-4 space-y-3">
                    <p class="text-xs text-gray-500">Bagikan link ini kepada donatur untuk melihat status kurban secara langsung.</p>
                    <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                        <span id="donor-link-text" class="flex-1 text-xs font-mono text-gray-700 truncate">{{ route('sacrifice.show', ['slug' => $sacrifice->public_slug]) }}</span>
                        <button onclick="copyDonorLink()"
                                id="copy-btn"
                                title="Salin link"
                                class="shrink-0 inline-flex items-center gap-1.5 text-xs font-medium text-[#1D9E75] hover:text-[#157a5a] transition-colors">
                            <i id="copy-icon" class="ti ti-copy text-sm"></i>
                            <span id="copy-label">Salin</span>
                        </button>
                    </div>
                    <a href="{{ route('sacrifice.show', ['slug' => $sacrifice->public_slug]) }}"
                       target="_blank"
                       class="w-full inline-flex items-center justify-center gap-2 border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                        <i class="ti ti-external-link text-sm"></i>
                        Buka Portal Donatur
                    </a>
                </div>
            </div>

            {{-- Certificate --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <i class="ti ti-certificate text-[#1D9E75]"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Sertifikat</h3>
                </div>
                <div class="p-4">
                    @if($sacrifice->hasCertificate())
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="ti ti-check text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Sertifikat Tersedia</p>
                            <p class="text-xs text-gray-400">Dibuat: {{ $sacrifice->certificate_generated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="ti ti-certificate-off text-gray-400 text-sm"></i>
                        </div>
                        <p class="text-sm text-gray-500">Sertifikat belum diterbitkan</p>
                    </div>
                    @endif
                    @if(auth()->user()->canWrite())
                    <form method="POST" action="{{ route('admin.sacrifices.certificate.generate', $sacrifice) }}">
                        @csrf
                        <button type="submit"
                                class="w-full {{ $sacrifice->hasCertificate() ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-[#1D9E75] hover:bg-[#157a5a] text-white' }} text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="ti ti-{{ $sacrifice->hasCertificate() ? 'refresh' : 'certificate' }}"></i>
                            {{ $sacrifice->hasCertificate() ? 'Buat Ulang Sertifikat' : 'Buat Sertifikat' }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Notes --}}
            @if($sacrifice->notes)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-amber-700 mb-1 flex items-center gap-1"><i class="ti ti-notes"></i> Catatan</p>
                <p class="text-sm text-amber-800">{{ $sacrifice->notes }}</p>
            </div>
            @endif

            {{-- Danger zone (admin only) --}}
            @if(auth()->user()->canDelete())
            <div class="bg-white rounded-xl border border-red-100 overflow-hidden">
                <div class="px-5 py-3 bg-red-50 border-b border-red-100 flex items-center gap-2">
                    <i class="ti ti-alert-triangle text-red-500"></i>
                    <h3 class="font-semibold text-red-700 text-sm">Zona Berbahaya</h3>
                </div>
                <div class="p-4">
                    <form method="POST" action="{{ route('admin.sacrifices.destroy', $sacrifice) }}"
                          onsubmit="return confirm('PERINGATAN: Menghapus {{ $sacrifice->reference_code }} akan menghapus semua data termasuk foto. Tindakan ini tidak dapat dibatalkan. Lanjutkan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="ti ti-trash"></i>
                            Hapus Data Kurban Ini
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Gallery section --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Manajemen Foto Dokumentasi</h3>
        </div>

        {{-- Upload form (staff + admin) --}}
        @if(auth()->user()->canWrite())
        <div class="p-5 border-b border-gray-100 bg-gray-50">
            <form method="POST" action="{{ route('admin.sacrifices.photos.upload', $sacrifice) }}" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Kategori Foto</label>
                        <select name="photo_category"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                            @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-40">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Keterangan (opsional)</label>
                        <input type="text" name="caption"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                               placeholder="Keterangan foto">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Pilih File (max 5MB/foto)</label>
                        <input type="file" name="photos[]" multiple accept="image/*"
                               class="block text-sm text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#1D9E75]/10 file:text-[#1D9E75] hover:file:bg-[#1D9E75]/20">
                    </div>
                    <button type="submit"
                            class="bg-[#1D9E75] hover:bg-[#157a5a] text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ti ti-upload"></i>
                        Upload
                    </button>
                </div>
            </form>
        </div>
        @endif

        {{-- Gallery grid --}}
        <div class="p-5">
            @if($photosByCategory->isEmpty())
            <div class="py-8 text-center">
                <i class="ti ti-photo text-gray-300 text-3xl mb-2"></i>
                <p class="text-gray-400 text-sm">Belum ada foto. Upload foto menggunakan form di atas.</p>
            </div>
            @else
            <div class="space-y-5">
                @foreach($categories as $catKey => $catLabel)
                    @if(isset($photosByCategory[$catKey]) && $photosByCategory[$catKey]->isNotEmpty())
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            {{ $catLabel }} ({{ $photosByCategory[$catKey]->count() }})
                        </p>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                            @foreach($photosByCategory[$catKey] as $photo)
                            <div class="group relative aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                <img src="{{ $photo->url }}" alt="{{ $photo->file_name }}"
                                     class="w-full h-full object-cover">
                                @if(auth()->user()->canDelete())
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-colors flex items-center justify-center">
                                    <form method="POST" action="{{ route('admin.galleries.delete', $photo) }}"
                                          class="opacity-0 group-hover:opacity-100 transition-opacity"
                                          onsubmit="return confirm('Hapus foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center">
                                            <i class="ti ti-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyDonorLink() {
    const url = document.getElementById('donor-link-text').textContent.trim();

    function onCopied() {
        const icon  = document.getElementById('copy-icon');
        const label = document.getElementById('copy-label');
        icon.classList.replace('ti-copy', 'ti-check');
        label.textContent = 'Tersalin!';
        setTimeout(() => {
            icon.classList.replace('ti-check', 'ti-copy');
            label.textContent = 'Salin';
        }, 2000);
    }

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(onCopied);
    } else {
        const el = document.createElement('textarea');
        el.value = url;
        el.style.cssText = 'position:fixed;left:-9999px;top:-9999px';
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        onCopied();
    }
}
</script>
@endpush
@endsection
