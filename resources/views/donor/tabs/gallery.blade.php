@php
    $categories = [
        'hewan' => ['label' => 'Foto Hewan', 'icon' => 'ti-paw'],
        'penyembelihan' => ['label' => 'Penyembelihan', 'icon' => 'ti-cut'],
        'pengemasan' => ['label' => 'Pengemasan', 'icon' => 'ti-package'],
        'distribusi' => ['label' => 'Distribusi', 'icon' => 'ti-truck-delivery'],
    ];
@endphp

@if($photosByCategory->isEmpty())
<div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <i class="ti ti-photo-off text-gray-400 text-2xl"></i>
    </div>
    <p class="text-gray-500 font-medium">Belum Ada Foto</p>
    <p class="text-gray-400 text-sm mt-1">Foto dokumentasi akan tersedia setelah proses berlangsung</p>
</div>
@else
<div class="space-y-5">
    @foreach($categories as $catKey => $catInfo)
        @if(isset($photosByCategory[$catKey]) && $photosByCategory[$catKey]->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <i class="ti {{ $catInfo['icon'] }} text-[#1D9E75]"></i>
                <h3 class="font-semibold text-gray-800 text-sm">{{ $catInfo['label'] }}</h3>
                <span class="ml-auto bg-gray-200 text-gray-600 text-xs font-medium px-2 py-0.5 rounded-full">
                    {{ $photosByCategory[$catKey]->count() }} foto
                </span>
            </div>
            <div class="p-4 grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($photosByCategory[$catKey] as $photo)
                <div class="group relative aspect-square overflow-hidden rounded-lg bg-gray-100 cursor-pointer"
                     onclick="openLightbox('{{ $photo->url }}')">
                    <img src="{{ $photo->url }}"
                         alt="{{ $photo->caption ?? $photo->category_label }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                        <i class="ti ti-zoom-in text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                    @if($photo->caption)
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-2 py-2">
                        <p class="text-white text-xs truncate">{{ $photo->caption }}</p>
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
