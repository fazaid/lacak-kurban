@extends('layouts.admin')

@section('title', 'Update Progress ' . $sacrifice->reference_code)
@section('page-title', 'Update Progress Kurban')
@section('breadcrumb', 'Admin / Data Kurban / ' . $sacrifice->reference_code . ' / Progress')

@section('content')
<div class="max-w-xl pt-2">
    <div class="bg-[#1D9E75]/10 border border-[#1D9E75]/20 rounded-xl px-4 py-3 flex items-center gap-3 mb-5">
        <i class="ti ti-info-circle text-[#1D9E75]"></i>
        <div>
            <p class="text-sm text-[#1D9E75] font-semibold">{{ $sacrifice->reference_code }}</p>
            <p class="text-xs text-[#1D9E75]/70">{{ $sacrifice->donor_name }} — {{ $sacrifice->animal_type }}</p>
        </div>
        <span class="ml-auto text-sm font-bold text-[#1D9E75]">{{ $sacrifice->getProgressPercentage() }}%</span>
    </div>

    <form method="POST" action="{{ route('admin.sacrifices.progress.update', $sacrifice) }}">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            @php
                $stages = [
                    ['key' => 'purchase', 'label' => 'Pembelian Hewan', 'icon' => 'ti-shopping-cart',
                     'status_field' => 'status_purchase', 'date_field' => 'date_purchase_completed'],
                    ['key' => 'slaughter', 'label' => 'Penyembelihan', 'icon' => 'ti-cut',
                     'status_field' => 'status_slaughter', 'date_field' => 'date_slaughter_completed'],
                    ['key' => 'distribution', 'label' => 'Distribusi Daging', 'icon' => 'ti-truck-delivery',
                     'status_field' => 'status_distribution', 'date_field' => 'date_distribution_completed'],
                    ['key' => 'report', 'label' => 'Laporan', 'icon' => 'ti-report',
                     'status_field' => 'status_report', 'date_field' => 'date_report_completed'],
                ];
            @endphp

            @foreach($stages as $stage)
            @php
                $currentStatus = old($stage['status_field'], $sacrifice->{$stage['status_field']});
                $currentDate = old($stage['date_field'], $sacrifice->{$stage['date_field']}?->format('Y-m-d'));
                $stepNum = $loop->iteration;
            @endphp
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                        {{ $currentStatus === 'completed' ? 'bg-[#1D9E75] text-white' : 'bg-gray-100 text-gray-500' }}">
                        {{ $stepNum }}
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ti {{ $stage['icon'] }} text-gray-600"></i>
                        <h3 class="font-semibold text-gray-800 text-sm">{{ $stage['label'] }}</h3>
                    </div>
                </div>
                <div class="p-5 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="{{ $stage['status_field'] }}" value="pending"
                                       {{ $currentStatus === 'pending' ? 'checked' : '' }}
                                       class="accent-[#1D9E75]">
                                <span class="text-sm text-gray-700">Menunggu</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="{{ $stage['status_field'] }}" value="completed"
                                       {{ $currentStatus === 'completed' ? 'checked' : '' }}
                                       class="accent-[#1D9E75]">
                                <span class="text-sm text-green-700 font-medium">Selesai</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="{{ $stage['date_field'] }}" value="{{ $currentDate }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Visual progress preview --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-4">
            <div class="flex-1">
                <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                    <span>Progress</span>
                    <span id="progress-pct-label" class="font-semibold text-[#1D9E75]">{{ $sacrifice->getProgressPercentage() }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div id="progress-bar" class="bg-[#1D9E75] h-full rounded-full transition-all duration-300"
                         style="width: {{ $sacrifice->getProgressPercentage() }}%"></div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit"
                    class="bg-[#1D9E75] hover:bg-[#157a5a] text-white font-semibold px-6 py-2.5 rounded-lg transition-colors flex items-center gap-2">
                <i class="ti ti-device-floppy"></i>
                Simpan Progress
            </button>
            <a href="{{ route('admin.sacrifices.show', $sacrifice) }}"
               class="border border-gray-300 text-gray-700 font-medium px-6 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@push('scripts')
<script>
(function () {
    const today = new Date().toISOString().slice(0, 10);

    // Auto-fill date when "completed" is chosen and date is blank; clear when "pending"
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const card     = this.closest('.bg-white');
            const dateInput = card?.querySelector('input[type="date"]');
            if (!dateInput) return;

            if (this.value === 'completed' && !dateInput.value) {
                dateInput.value = today;
            } else if (this.value === 'pending') {
                dateInput.value = '';
            }

            updatePreview();
        });
    });

    // Live progress bar preview
    function updatePreview() {
        const radios = document.querySelectorAll('input[type="radio"][value="completed"]:checked');
        const pct    = Math.round(radios.length / 4 * 100);
        const bar    = document.getElementById('progress-bar');
        const label  = document.getElementById('progress-pct-label');
        if (bar)   bar.style.width   = pct + '%';
        if (label) label.textContent = pct + '%';
    }

    updatePreview();
})();
</script>
@endpush

@endsection
