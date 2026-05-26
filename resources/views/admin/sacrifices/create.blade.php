@extends('layouts.admin')

@section('title', 'Tambah Kurban')
@section('page-title', 'Tambah Kurban Baru')
@section('breadcrumb', 'Admin / Data Kurban / Tambah')

@section('content')
<div class="max-w-2xl pt-2">
    <form method="POST" action="{{ route('admin.sacrifices.store') }}" class="space-y-5">
        @csrf

        {{-- Donor Info --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-user-circle text-[#1D9E75]"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Informasi Donatur</h3>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Donatur <span class="text-red-500">*</span></label>
                    <input type="text" name="donor_name" value="{{ old('donor_name') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] @error('donor_name') border-red-400 @enderror"
                           placeholder="Nama lengkap donatur">
                    @error('donor_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Satu donatur dapat mendaftarkan lebih dari satu kurban.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Donatur</label>
                        <input type="email" name="donor_email" value="{{ old('donor_email') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                               placeholder="email@contoh.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="donor_phone" value="{{ old('donor_phone') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                               placeholder="08xxx">
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
                            {{ old('sacrifice_type', 'nusantara') === 'nusantara' ? 'border-[#1D9E75] bg-[#1D9E75]/5' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="sacrifice_type" value="nusantara" class="sr-only"
                                   {{ old('sacrifice_type', 'nusantara') === 'nusantara' ? 'checked' : '' }}>
                            <div class="w-4 h-4 rounded-full border-2 {{ old('sacrifice_type', 'nusantara') === 'nusantara' ? 'border-[#1D9E75]' : 'border-gray-300' }} flex items-center justify-center flex-shrink-0">
                                <div class="w-2 h-2 rounded-full bg-[#1D9E75] {{ old('sacrifice_type', 'nusantara') !== 'nusantara' ? 'hidden' : '' }}"></div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Nusantara</p>
                                <p class="text-xs text-gray-500">Kurban dalam negeri (Sapi / Domba)</p>
                            </div>
                        </label>
                        <label class="flex-1 flex items-center gap-3 border rounded-lg px-4 py-3 cursor-pointer transition-colors
                            {{ old('sacrifice_type') === 'palestina' ? 'border-[#1D9E75] bg-[#1D9E75]/5' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="sacrifice_type" value="palestina" class="sr-only"
                                   {{ old('sacrifice_type') === 'palestina' ? 'checked' : '' }}>
                            <div class="w-4 h-4 rounded-full border-2 {{ old('sacrifice_type') === 'palestina' ? 'border-[#1D9E75]' : 'border-gray-300' }} flex items-center justify-center flex-shrink-0">
                                <div class="w-2 h-2 rounded-full bg-[#1D9E75] {{ old('sacrifice_type') !== 'palestina' ? 'hidden' : '' }}"></div>
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
                            data-init="{{ old('animal_type', '') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] @error('animal_type') border-red-400 @enderror">
                        <option value="">Pilih jenis hewan...</option>
                        <option value="sapi"  {{ old('animal_type') === 'sapi'  ? 'selected' : '' }}>Sapi</option>
                        <option value="domba" {{ old('animal_type') === 'domba' ? 'selected' : '' }}>Domba</option>
                        <option value="unta"  {{ old('animal_type') === 'unta'  ? 'selected' : '' }} class="unta-option" style="display:none">Unta (Palestina)</option>
                    </select>
                    @error('animal_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Sharing type (populated by JS) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kepemilikan <span class="text-red-500">*</span></label>
                    <select name="sharing_type" id="sharing_type"
                            data-init="{{ old('sharing_type', '') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] @error('sharing_type') border-red-400 @enderror">
                        <option value="">Pilih kepemilikan...</option>
                    </select>
                    @error('sharing_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Share ratio (shown only when collective) --}}
                <div id="ratio-section" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Bagian Anda <span class="text-xs text-gray-400 font-normal" id="ratio-hint"></span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="share_ratio" id="share_ratio"
                               value="{{ old('share_ratio', 1) }}" min="1" max="10"
                               class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                        <span class="text-sm text-gray-500" id="ratio-suffix"></span>
                    </div>
                    @error('share_ratio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Hidden fallback: share_ratio=1 when full (ratio-section hidden but input still submits) --}}
                <input type="hidden" id="share_ratio_full" name="share_ratio" value="1">

                {{-- Animal price --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm pointer-events-none">Rp</span>
                        <input type="number" name="animal_price" value="{{ old('animal_price') }}" step="1000" min="0"
                               class="w-full pl-9 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                               placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pembelian</label>
                    <input type="text" name="purchase_location" value="{{ old('purchase_location') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                           placeholder="Nama pasar / peternak">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Penyembelihan</label>
                    <input type="text" name="slaughter_location" value="{{ old('slaughter_location') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                           placeholder="Nama RPH / masjid / lokasi">
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
                    <input type="text" name="beneficiary_name" value="{{ old('beneficiary_name') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                           placeholder="Nama / lembaga penerima">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Penerima</label>
                    <input type="text" name="beneficiary_type" value="{{ old('beneficiary_type') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                           placeholder="Keluarga / Panti Asuhan / Masjid...">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Penerima</label>
                    <textarea name="beneficiary_address" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                              placeholder="Alamat lengkap penerima manfaat">{{ old('beneficiary_address') }}</textarea>
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
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]"
                          placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-[#1D9E75] hover:bg-[#157a5a] text-white font-semibold px-6 py-2.5 rounded-lg transition-colors flex items-center gap-2">
                <i class="ti ti-device-floppy"></i>
                Simpan Data
            </button>
            <a href="{{ route('admin.sacrifices.index') }}"
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
    // unta and sapi both support full + collective; domba = full only
    const ANIMAL_CONFIGS = {
        unta:  { options: [{ value: 'full', label: 'Penuh — 1 orang punya 1 unta' },
                            { value: 'collective', label: 'Kolektif 1/10 — berbagi 10 orang' }], maxRatio: 10 },
        sapi:  { options: [{ value: 'full', label: 'Penuh — 1 orang punya 1 sapi' },
                            { value: 'collective', label: 'Kolektif 1/7 — berbagi 7 orang' }],  maxRatio: 7 },
        domba: { options: [{ value: 'full', label: 'Penuh — 1 orang punya 1 domba' }],          maxRatio: 1 },
    };

    const typeRadios    = document.querySelectorAll('input[name="sacrifice_type"]');
    const animalSelect  = document.getElementById('animal_type');
    const untaOption    = animalSelect.querySelector('.unta-option');
    const sharingSelect = document.getElementById('sharing_type');
    const ratioSection  = document.getElementById('ratio-section');
    const ratioInput    = document.getElementById('share_ratio');
    const ratioFullInput = document.getElementById('share_ratio_full');
    const ratioHint     = document.getElementById('ratio-hint');
    const ratioSuffix   = document.getElementById('ratio-suffix');

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
            // Restore previous selection if still valid
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
            ratioFullInput.disabled = true;   // Disable hidden fallback so only real input submits
            ratioInput.max = config.maxRatio;
            if (!ratioInput.value || parseInt(ratioInput.value) > config.maxRatio) ratioInput.value = 1;
            ratioHint.textContent   = `(1–${config.maxRatio})`;
            ratioSuffix.textContent = `dari ${config.maxRatio} bagian`;
        } else {
            ratioSection.classList.add('hidden');
            ratioFullInput.disabled = false;  // Re-enable fallback (submits 1)
            ratioFullInput.value    = 1;
        }
        // Keep data-init in sync so animal change can still restore sharing
        sharingSelect.dataset.init = sharingSelect.value;
    }

    typeRadios.forEach(r    => r.addEventListener('change', onSacrificeTypeChange));
    animalSelect.addEventListener('change', onAnimalTypeChange);
    sharingSelect.addEventListener('change', onSharingTypeChange);

    // Initialize on page load
    onSacrificeTypeChange();
})();
</script>
@endpush
