@extends('layouts.admin')

@section('title', 'Data Kurban')
@section('page-title', 'Data Kurban')
@section('breadcrumb', 'Admin / Data Kurban')

@section('header-actions')
    <a href="{{ route('admin.sacrifices.create') }}"
       class="inline-flex items-center gap-2 bg-[#1D9E75] hover:bg-[#157a5a] text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
        <i class="ti ti-plus"></i>
        Tambah Kurban
    </a>
@endsection

@php
    $filterKeys        = ['search', 'donor_name', 'sacrifice_type', 'animal_type', 'status', 'date_from', 'date_to'];
    $activeCount       = collect($filterKeys)->filter(fn($k) => request()->filled($k))->count();
    $hasFilters        = $activeCount > 0;

    $statusLabels      = ['completed' => 'Selesai', 'in_progress' => 'Sedang Berjalan', 'pending' => 'Menunggu'];
    $animalLabels      = ['sapi' => 'Sapi', 'domba' => 'Domba', 'unta' => 'Unta'];
    $sacrificeTypeLabels = ['nusantara' => 'Nusantara', 'palestina' => 'Palestina'];
@endphp

@section('content')
<div class="space-y-4 pt-2">

    {{-- ── Filter panel ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.sacrifices.index') }}" id="filter-form" class="space-y-3">

            {{-- Row 1: text search + dropdowns --}}
            <div class="flex flex-wrap gap-3">

                {{-- Full-text search --}}
                <div class="flex-1 min-w-52 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-search text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Kode referensi, email, telepon..."
                           class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] focus:border-transparent">
                </div>

                {{-- Donor name --}}
                <div class="relative min-w-44">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-user text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" name="donor_name" value="{{ request('donor_name') }}"
                           placeholder="Nama donatur"
                           class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] focus:border-transparent
                                  {{ request()->filled('donor_name') ? 'border-[#1D9E75] bg-[#1D9E75]/5' : '' }}">
                </div>

                {{-- Sacrifice type --}}
                <select name="sacrifice_type"
                        class="border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1D9E75]
                               {{ request()->filled('sacrifice_type') ? 'border-[#1D9E75] bg-[#1D9E75]/5 text-[#1D9E75] font-medium' : 'border-gray-300' }}">
                    <option value="">Semua Program</option>
                    @foreach($sacrificeTypeLabels as $val => $label)
                    <option value="{{ $val }}" {{ request('sacrifice_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                {{-- Animal type --}}
                <select name="animal_type"
                        class="border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1D9E75]
                               {{ request()->filled('animal_type') ? 'border-[#1D9E75] bg-[#1D9E75]/5 text-[#1D9E75] font-medium' : 'border-gray-300' }}">
                    <option value="">Semua Hewan</option>
                    @foreach($animalLabels as $val => $label)
                    <option value="{{ $val }}" {{ request('animal_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                {{-- Status --}}
                <select name="status"
                        class="border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1D9E75]
                               {{ request()->filled('status') ? 'border-[#1D9E75] bg-[#1D9E75]/5 text-[#1D9E75] font-medium' : 'border-gray-300' }}">
                    <option value="">Semua Status</option>
                    @foreach($statusLabels as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

            </div>

            {{-- Row 2: date range + actions --}}
            <div class="flex flex-wrap items-center gap-3">

                <span class="text-xs font-medium text-gray-500 whitespace-nowrap">Tgl Daftar:</span>
                <div class="flex items-center gap-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1D9E75]
                                  {{ request()->filled('date_from') ? 'border-[#1D9E75] bg-[#1D9E75]/5' : 'border-gray-300' }}">
                    <span class="text-gray-400 text-sm">—</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           min="{{ request('date_from') }}"
                           class="border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1D9E75]
                                  {{ request()->filled('date_to') ? 'border-[#1D9E75] bg-[#1D9E75]/5' : 'border-gray-300' }}">
                </div>

                <div class="flex items-center gap-2 ml-auto">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-[#1D9E75] hover:bg-[#157a5a] text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                        <i class="ti ti-filter text-sm"></i>
                        Filter
                        @if($activeCount > 0)
                        <span class="bg-white/30 text-white text-xs font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $activeCount }}</span>
                        @endif
                    </button>
                    @if($hasFilters)
                    <a href="{{ route('admin.sacrifices.index') }}"
                       class="inline-flex items-center gap-1.5 border border-gray-300 text-gray-600 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="ti ti-x text-sm"></i>
                        Reset
                    </a>
                    @endif
                </div>

            </div>

        </form>

        {{-- Active filter chips --}}
        @if($hasFilters)
        <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-gray-100">
            <span class="text-xs text-gray-400 self-center">Filter aktif:</span>
            @if(request()->filled('search'))
            <span class="inline-flex items-center gap-1 bg-[#1D9E75]/10 text-[#1D9E75] text-xs font-medium px-2.5 py-1 rounded-full">
                <i class="ti ti-search text-xs"></i> "{{ request('search') }}"
                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-0.5 hover:text-[#157a5a]"><i class="ti ti-x text-xs"></i></a>
            </span>
            @endif
            @if(request()->filled('donor_name'))
            <span class="inline-flex items-center gap-1 bg-[#1D9E75]/10 text-[#1D9E75] text-xs font-medium px-2.5 py-1 rounded-full">
                <i class="ti ti-user text-xs"></i> {{ request('donor_name') }}
                <a href="{{ request()->fullUrlWithQuery(['donor_name' => null]) }}" class="ml-0.5 hover:text-[#157a5a]"><i class="ti ti-x text-xs"></i></a>
            </span>
            @endif
            @if(request()->filled('sacrifice_type'))
            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full">
                <i class="ti ti-globe text-xs"></i> {{ $sacrificeTypeLabels[request('sacrifice_type')] ?? request('sacrifice_type') }}
                <a href="{{ request()->fullUrlWithQuery(['sacrifice_type' => null]) }}" class="ml-0.5 hover:text-emerald-800"><i class="ti ti-x text-xs"></i></a>
            </span>
            @endif
            @if(request()->filled('animal_type'))
            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs font-medium px-2.5 py-1 rounded-full">
                <i class="ti ti-pig text-xs"></i> {{ $animalLabels[request('animal_type')] ?? request('animal_type') }}
                <a href="{{ request()->fullUrlWithQuery(['animal_type' => null]) }}" class="ml-0.5 hover:text-blue-800"><i class="ti ti-x text-xs"></i></a>
            </span>
            @endif
            @if(request()->filled('status'))
            <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-600 text-xs font-medium px-2.5 py-1 rounded-full">
                <i class="ti ti-circle-dot text-xs"></i> {{ $statusLabels[request('status')] ?? request('status') }}
                <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-0.5 hover:text-purple-800"><i class="ti ti-x text-xs"></i></a>
            </span>
            @endif
            @if(request()->filled('date_from') || request()->filled('date_to'))
            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-600 text-xs font-medium px-2.5 py-1 rounded-full">
                <i class="ti ti-calendar text-xs"></i>
                {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : '…' }}
                —
                {{ request('date_to')   ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y')   : '…' }}
                <a href="{{ request()->fullUrlWithQuery(['date_from' => null, 'date_to' => null]) }}" class="ml-0.5 hover:text-amber-800"><i class="ti ti-x text-xs"></i></a>
            </span>
            @endif
        </div>
        @endif
    </div>

    {{-- ── Result summary ───────────────────────────────────────────── --}}
    <div class="flex items-center justify-between text-sm text-gray-500">
        <span>
            @if($hasFilters)
                Ditemukan <span class="font-semibold text-gray-800">{{ $sacrifices->total() }}</span>
                dari {{ $total }} data
            @else
                Total <span class="font-semibold text-gray-800">{{ $total }}</span> data kurban
            @endif
        </span>
        @if($sacrifices->hasPages())
        <span>Halaman {{ $sacrifices->currentPage() }} / {{ $sacrifices->lastPage() }}</span>
        @endif
    </div>

    {{-- ── Table ────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @if($sacrifices->isEmpty())
        <div class="py-16 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-mood-empty text-gray-400 text-2xl"></i>
            </div>
            <p class="text-gray-700 font-semibold mb-1">Tidak ada data ditemukan</p>
            @if($hasFilters)
            <p class="text-gray-400 text-sm mb-4">Coba ubah atau hapus beberapa filter</p>
            <a href="{{ route('admin.sacrifices.index') }}"
               class="inline-flex items-center gap-2 text-sm text-[#1D9E75] hover:underline">
                <i class="ti ti-refresh text-sm"></i> Tampilkan semua data
            </a>
            @endif
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3">Kode / Donatur</th>
                        <th class="px-5 py-3 hidden sm:table-cell">Hewan</th>
                        <th class="px-5 py-3 hidden lg:table-cell">Penerima</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 hidden md:table-cell">Tgl Daftar</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($sacrifices as $sacrifice)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <p class="font-mono text-xs font-semibold text-[#1D9E75]">{{ $sacrifice->reference_code }}</p>
                            <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $sacrifice->donor_name }}</p>
                            @if($sacrifice->donor_phone)
                            <p class="text-xs text-gray-400">{{ $sacrifice->donor_phone }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            <p class="text-sm font-medium text-gray-700">{{ $sacrifice->getAnimalTypeLabel() }}</p>
                            <span class="text-xs {{ $sacrifice->sacrifice_type === 'palestina' ? 'text-emerald-600' : 'text-blue-500' }}">
                                {{ $sacrifice->getSacrificeTypeLabel() }}
                            </span>
                        </td>
                        <td class="px-5 py-4 hidden lg:table-cell">
                            <p class="text-sm text-gray-700 truncate max-w-[180px]">{{ $sacrifice->beneficiary_name ?? '—' }}</p>
                            @if($sacrifice->beneficiary_type)
                            <p class="text-xs text-gray-400">{{ $sacrifice->beneficiary_type }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-full {{ $sacrifice->getStatusBadgeClass() }}">
                                {{ $sacrifice->getStatusLabel() }}
                            </span>
                            <div class="flex items-center gap-1.5 mt-1.5">
                                <div class="flex-1 max-w-[80px] bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-[#1D9E75] h-full rounded-full" style="width: {{ $sacrifice->getProgressPercentage() }}%"></div>
                                </div>
                                <span class="text-xs text-gray-400">{{ $sacrifice->getProgressPercentage() }}%</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 hidden md:table-cell text-xs text-gray-500">
                            {{ $sacrifice->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.sacrifices.show', $sacrifice) }}"
                                   title="Detail"
                                   class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-[#1D9E75] hover:text-white text-gray-600 flex items-center justify-center transition-colors">
                                    <i class="ti ti-eye text-sm"></i>
                                </a>
                                <button onclick="copyDonorLink(this, '{{ route('sacrifice.show', ['slug' => $sacrifice->public_slug]) }}')"
                                        title="Salin Link Portal Donatur"
                                        class="copy-btn w-8 h-8 rounded-lg bg-[#1D9E75]/10 hover:bg-[#1D9E75] text-[#1D9E75] hover:text-white flex items-center justify-center transition-all duration-200 hover:scale-110 active:scale-95">
                                    <i class="ti ti-link text-sm"></i>
                                </button>
                                @if(auth()->user()->canWrite())
                                <a href="{{ route('admin.sacrifices.edit', $sacrifice) }}"
                                   title="Edit"
                                   class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-blue-500 hover:text-white text-gray-600 flex items-center justify-center transition-colors">
                                    <i class="ti ti-edit text-sm"></i>
                                </a>
                                <a href="{{ route('admin.sacrifices.progress.edit', $sacrifice) }}"
                                   title="Update Progress"
                                   class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-amber-500 hover:text-white text-gray-600 flex items-center justify-center transition-colors">
                                    <i class="ti ti-timeline text-sm"></i>
                                </a>
                                @endif
                                @if(auth()->user()->canDelete())
                                <form method="POST" action="{{ route('admin.sacrifices.destroy', $sacrifice) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus data kurban {{ $sacrifice->reference_code }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            title="Hapus"
                                            class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-red-500 hover:text-white text-gray-600 flex items-center justify-center transition-colors">
                                        <i class="ti ti-trash text-sm"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($sacrifices->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between gap-4">
            <p class="text-xs text-gray-500 shrink-0">
                Menampilkan {{ $sacrifices->firstItem() }}–{{ $sacrifices->lastItem() }}
                dari {{ $sacrifices->total() }} hasil
            </p>
            {{ $sacrifices->links() }}
        </div>
        @endif
        @endif
    </div>

</div>

{{-- Toast notifikasi salin link --}}
<div id="copy-toast"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-lg flex items-center gap-2 opacity-0 pointer-events-none transition-opacity duration-300 z-50">
    <i class="ti ti-check text-[#1D9E75]"></i>
    Link portal donatur berhasil disalin!
</div>

@push('scripts')
<script>
function copyDonorLink(btn, url) {
    function onCopied() {
        // Feedback pada tombol
        const icon = btn.querySelector('i');
        icon.classList.replace('ti-link', 'ti-check');
        btn.classList.remove('bg-[#1D9E75]/10', 'text-[#1D9E75]');
        btn.classList.add('bg-green-500', 'text-white');
        btn.title = 'Tersalin!';

        // Toast
        const toast = document.getElementById('copy-toast');
        toast.classList.remove('opacity-0');
        toast.classList.add('opacity-100');

        setTimeout(() => {
            icon.classList.replace('ti-check', 'ti-link');
            btn.classList.add('bg-[#1D9E75]/10', 'text-[#1D9E75]');
            btn.classList.remove('bg-green-500', 'text-white');
            btn.title = 'Salin Link Portal Donatur';
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0');
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
