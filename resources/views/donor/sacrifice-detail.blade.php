@extends('layouts.app')

@section('title', 'Kurban ' . $sacrifice->reference_code . ' — NPC Kurban Tracker')

@section('content')
<div class="min-h-screen flex flex-col">
    {{-- Top bar --}}
    <header class="bg-[#1D9E75] text-white">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center gap-4">
            <a href="{{ route('home') }}" class="text-white/80 hover:text-white transition-colors">
                <i class="ti ti-arrow-left text-xl"></i>
            </a>
            <div class="flex-1">
                <p class="text-xs text-white/70 uppercase tracking-wider font-medium">NPC Kurban Tracker</p>
                <h1 class="font-bold text-base">{{ $sacrifice->reference_code }}</h1>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                @if($sacrifice->getProgressPercentage() === 100) bg-green-500 text-white
                @elseif($sacrifice->getProgressPercentage() === 0) bg-yellow-400 text-yellow-900
                @else bg-blue-500 text-white @endif">
                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                {{ $sacrifice->getStatusLabel() }}
            </span>
        </div>

        {{-- Progress bar --}}
        <div class="bg-white/20 h-1.5">
            <div class="bg-white h-full transition-all duration-500"
                 style="width: {{ $sacrifice->getProgressPercentage() }}%"></div>
        </div>
    </header>

    {{-- Tab navigation --}}
    <div class="bg-white border-b border-gray-200 sticky top-14 z-10 shadow-sm">
        <div class="max-w-3xl mx-auto px-4">
            <div class="flex overflow-x-auto -mb-px scrollbar-hide" id="nav-tabs">
                @foreach([
                    ['id' => 'profile', 'label' => 'Profil', 'icon' => 'ti-user'],
                    ['id' => 'progress', 'label' => 'Progress', 'icon' => 'ti-timeline'],
                    ['id' => 'gallery', 'label' => 'Galeri', 'icon' => 'ti-photo'],
                    ['id' => 'certificate', 'label' => 'Sertifikat', 'icon' => 'ti-certificate'],
                ] as $tab)
                    <button
                        data-tab="{{ $tab['id'] }}"
                        onclick="switchTab('{{ $tab['id'] }}')"
                        class="tab-btn flex items-center gap-2 px-4 py-3.5 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
                               {{ $tab['id'] === $activeTab ? 'border-[#1D9E75] text-[#1D9E75]' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
                    >
                        <i class="ti {{ $tab['icon'] }}"></i>
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tab content --}}
    <main class="flex-1 max-w-3xl mx-auto w-full px-4 py-6">
        @foreach([
            ['id' => 'profile',      'view' => 'donor.tabs.profile'],
            ['id' => 'progress',     'view' => 'donor.tabs.progress'],
            ['id' => 'gallery',      'view' => 'donor.tabs.gallery'],
            ['id' => 'certificate',  'view' => 'donor.tabs.certificate'],
        ] as $panel)
        <div id="tab-{{ $panel['id'] }}"{{ $panel['id'] !== $activeTab ? ' class="hidden"' : '' }}>
            @include($panel['view'])
        </div>
        @endforeach
    </main>

</div>

{{-- Lightbox --}}
<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4" onclick="closeLightbox()">
    <button class="absolute top-4 right-4 text-white/70 hover:text-white text-2xl" onclick="closeLightbox()">
        <i class="ti ti-x"></i>
    </button>
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg" onclick="event.stopPropagation()">
</div>

@push('scripts')
<script>
function switchTab(tabId) {
    document.querySelectorAll('[id^="tab-"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-[#1D9E75]', 'text-[#1D9E75]');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    document.getElementById('tab-' + tabId).classList.remove('hidden');
    const activeBtn = document.querySelector(`[data-tab="${tabId}"]`);
    activeBtn.classList.add('border-[#1D9E75]', 'text-[#1D9E75]');
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
}

function openLightbox(src) {
    const lb = document.getElementById('lightbox');
    document.getElementById('lightbox-img').src = src;
    lb.classList.remove('hidden');
    lb.classList.add('flex');
}

function closeLightbox() {
    const lb = document.getElementById('lightbox');
    lb.classList.add('hidden');
    lb.classList.remove('flex');
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeLightbox();
});
</script>
@endpush
@endsection
