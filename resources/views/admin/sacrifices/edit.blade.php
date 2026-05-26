@extends('layouts.admin')

@section('title', 'Edit Kurban ' . $sacrifice->reference_code)
@section('page-title', 'Edit Data Kurban')
@section('breadcrumb', 'Admin / Data Kurban / ' . $sacrifice->reference_code . ' / Edit')

@section('content')
<div class="max-w-2xl pt-2">
    <div class="bg-[#1D9E75]/10 border border-[#1D9E75]/20 rounded-xl px-4 py-3 flex items-center gap-3 mb-5">
        <i class="ti ti-info-circle text-[#1D9E75]"></i>
        <p class="text-sm text-[#1D9E75] font-medium">Kode Referensi: <span class="font-mono font-bold">{{ $sacrifice->reference_code }}</span></p>
    </div>

    <form method="POST" action="{{ route('admin.sacrifices.update', $sacrifice) }}" class="space-y-5">
        @csrf
        @method('PUT')

        @php
            $cType    = old('sacrifice_type', $sacrifice->sacrifice_type);
            $cAnimal  = old('animal_type', $sacrifice->animal_type);
            $cSharing = old('sharing_type', $sacrifice->sharing_type);
            $cRatio   = old('share_ratio', $sacrifice->share_ratio ?? 1);
        @endphp

        {{-- Donor Info --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-user-circle text-[#1D9E75]"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Informasi Donatur</h3>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Donatur <span class="text-red-500">*</span></label>
                    <input type="text" name="donor_name" value="{{ old('donor_name', $sacrifice->donor_name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] @error('donor_name') border-red-400 @enderror">
                    @error('donor_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Donatur</label>
                        <input type="email" name="donor_email" value="{{ old('donor_email', $sacrifice->donor_email) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="donor_phone" value="{{ old('donor_phone', $sacrifice->donor_phone) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                    </div>
                </div>
            </div>
        </div>

        {{-- Animal Info --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-paw text-[#1D9E75]"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Informasi Hewan Kurban</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Sacrifice type --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Program <span class="text-red-500">*</span></label>
                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center gap-3 border rounded-lg px-4 py-3 cursor-pointer transition-colors
                            {{ $cType === 'nusantara' ? 'border-[#1D9E75] bg-[#1D9E75]/5' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="sacrifice_type" value="nusantara" class="sr-only"
                                   {{ $cType === 'nusantara' ? 'checked' : '' }}>
                            <div class="w-4 h-4 rounded-full border-2 {{ $cType === 'nusantara' ? 'border-[#1D9E75]' : 'border-gray-300' }} flex items-center justify-center flex-shrink-0">
                                <div class="w-2 h-2 rounded-full bg-[#1D9E75] {{ $cType !== 'nusantara' ? 'hidden' : '' }}"></div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Nusantara</p>
                                <p class="text-xs text-gray-500">Kurban dalam negeri (Sapi / Domba)</p>
                            </div>
                        </label>
                        <label class="flex-1 flex items-center gap-3 border rounded-lg px-4 py-3 cursor-pointer transition-colors
                            {{ $cType === 'palestina' ? 'border-[#1D9E75] bg-[#1D9E75]/5' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="sacrifice_type" value="palestina" class="sr-only"
                                   {{ $cType === 'palestina' ? 'checked' : '' }}>
                            <div class="w-4 h-4 rounded-full border-2 {{ $cType === 'palestina' ? 'border-[#1D9E75]' : 'border-gray-300' }} flex items-center justify-center flex-shrink-0">
                                <div class="w-2 h-2 rounded-full bg-[#1D9E75] {{ $cType !== 'palestina' ? 'hidden' : '' }}"></div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Palestina</p>
                                <p class="text-xs text-gray-500">Kurban untuk Palestina (Unta / Sapi / Domba)</p>
                            </div>
                        </label>
                    </div>
                    @error('sacrifice_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Animal type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Hewan <span class="text-red-500">*</span></label>
                    <select name="animal_type" id="animal_type"
                            data-init="{{ $cAnimal }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] @error('animal_type') border-red-400 @enderror">
                        <option value="">Pilih jenis hewan...</option>
                        <option value="sapi"  {{ $cAnimal === 'sapi'  ? 'selected' : '' }}>Sapi</option>
                        <option value="domba" {{ $cAnimal === 'domba' ? 'selected' : '' }}>Domba</option>
                        <option value="unta"  {{ $cAnimal === 'unta'  ? 'selected' : '' }} class="unta-option"
                                style="{{ $cType !== 'palestina' ? 'display:none' : '' }}">Unta (Palestina)</option>
                    </select>
                    @error('animal_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Sharing type (populated by JS) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kepemilikan <span class="text-red-500">*</span></label>
                    <select name="sharing_type" id="sharing_type"
                            data-init="{{ $cSharing }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] @error('sharing_type') border-red-400 @enderror">
                        <option value="">Pilih kepemilikan...</option>
                    </select>
                    @error('sharing_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Share ratio (shown only when collective) --}}
                <div id="ratio-section" class="{{ $cSharing === 'collective' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Bagian Anda <span class="text-xs text-gray-400 font-normal" id="ratio-hint">
                            @if($cSharing === 'collective')
                                (1–{{ $cAnimal === 'unta' ? 10 : 7 }})
                            @endif
                        </span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="share_ratio" id="share_ratio"
                               value="{{ $cRatio }}" min="1" max="{{ $cAnimal === 'unta' ? 10 : 7 }}"
                               class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                        <span class="text-sm text-gray-500" id="ratio-suffix">
                            @if($cSharing === 'collective')
                                dari {{ $cAnimal === 'unta' ? 10 : 7 }} bagian
                            @endif
                        </span>
                    </div>
                    @error('share_ratio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <input type="hidden" id="share_ratio_full" name="share_ratio" value="1"
                       {{ $cSharing === 'collective' ? 'disabled' : '' }}>

                {{-- Animal price --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm pointer-events-none">Rp</span>
                        <input type="number" name="animal_price" value="{{ old('animal_price', $sacrifice->animal_price) }}" step="1000" min="0"
                               class="w-full pl-9 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                               placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date', $sacrifice->purchase_date?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pembelian</label>
                    <input type="text" name="purchase_location" value="{{ old('purchase_location', $sacrifice->purchase_location) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Penyembelihan</label>
                    <input type="text" name="slaughter_location" value="{{ old('slaughter_location', $sacrifice->slaughter_location) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                </div>
            </div>
        </div>

        {{-- Beneficiary Info --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-heart-handshake text-[#1D9E75]"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Informasi Penerima Manfaat</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                    <input type="text" name="beneficiary_name" value="{{ old('beneficiary_name', $sacrifice->beneficiary_name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Penerima</label>
                    <input type="text" name="beneficiary_type" value="{{ old('beneficiary_type', $sacrifice->beneficiary_type) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Penerima</label>
                    <textarea name="beneficiary_address" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">{{ old('beneficiary_address', $sacrifice->beneficiary_address) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-notes text-[#1D9E75]"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Catatan</h3>
            </div>
            <div class="p-5">
                <textarea name="notes" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">{{ old('notes', $sacrifice->notes) }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-[#1D9E75] hover:bg-[#157a5a] text-white font-semibold px-6 py-2.5 rounded-lg transition-colors flex items-center gap-2">
                <i class="ti ti-device-floppy"></i>
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.sacrifices.show', $sacrifice) }}"
               class="border border-gray-300 text-gray-700 font-medium px-6 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const ANIMAL_CONFIGS = {
        unta:  { options: [{ value: 'full', label: 'Penuh — 1 orang punya 1 unta' },
                            { value: 'collective', label: 'Kolektif 1/10 — berbagi 10 orang' }], maxRatio: 10 },
        sapi:  { options: [{ value: 'full', label: 'Penuh — 1 orang punya 1 sapi' },
                            { value: 'collective', label: 'Kolektif 1/7 — berbagi 7 orang' }],  maxRatio: 7 },
        domba: { options: [{ value: 'full', label: 'Penuh — 1 orang punya 1 domba' }],          maxRatio: 1 },
    };

    const typeRadios     = document.querySelectorAll('input[name="sacrifice_type"]');
    const animalSelect   = document.getElementById('animal_type');
    const untaOption     = animalSelect.querySelector('.unta-option');
    const sharingSelect  = document.getElementById('sharing_type');
    const ratioSection   = document.getElementById('ratio-section');
    const ratioInput     = document.getElementById('share_ratio');
    const ratioFullInput = document.getElementById('share_ratio_full');
    const ratioHint      = document.getElementById('ratio-hint');
    const ratioSuffix    = document.getElementById('ratio-suffix');

    function getSacrificeType() {
        return document.querySelector('input[name="sacrifice_type"]:checked')?.value ?? 'nusantara';
    }

    function onSacrificeTypeChange() {
        const isPalestina = getSacrificeType() === 'palestina';
        untaOption.style.display = isPalestina ? '' : 'none';
        if (!isPalestina && animalSelect.value === 'unta') animalSelect.value = '';
        onAnimalTypeChange();
    }

    function onAnimalTypeChange() {
        const animal = animalSelect.value;
        const config = ANIMAL_CONFIGS[animal];
        const prev   = sharingSelect.dataset.init || sharingSelect.value;

        sharingSelect.innerHTML = '<option value="">Pilih kepemilikan...</option>';
        if (config) {
            config.options.forEach(o => {
                const el = document.createElement('option');
                el.value = o.value; el.textContent = o.label;
                sharingSelect.appendChild(el);
            });
            const valid = config.options.some(o => o.value === prev);
            sharingSelect.value = valid ? prev : (config.options.length === 1 ? config.options[0].value : '');
        }
        onSharingTypeChange();
    }

    function onSharingTypeChange() {
        const animal  = animalSelect.value;
        const sharing = sharingSelect.value;
        const config  = ANIMAL_CONFIGS[animal];

        if (sharing === 'collective' && config) {
            ratioSection.classList.remove('hidden');
            ratioFullInput.disabled = true;
            ratioInput.max = config.maxRatio;
            if (!ratioInput.value || parseInt(ratioInput.value) > config.maxRatio) ratioInput.value = 1;
            ratioHint.textContent   = `(1–${config.maxRatio})`;
            ratioSuffix.textContent = `dari ${config.maxRatio} bagian`;
        } else {
            ratioSection.classList.add('hidden');
            ratioFullInput.disabled = false;
            ratioFullInput.value    = 1;
        }
        sharingSelect.dataset.init = sharingSelect.value;
    }

    typeRadios.forEach(r => r.addEventListener('change', onSacrificeTypeChange));
    animalSelect.addEventListener('change', onAnimalTypeChange);
    sharingSelect.addEventListener('change', onSharingTypeChange);

    // Initialize: rebuild options and restore existing selections
    onSacrificeTypeChange();
})();
</script>
@endpush
