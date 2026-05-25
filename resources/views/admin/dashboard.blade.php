@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6 pt-2">
    {{-- Stats grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-[#1D9E75]/10 rounded-lg flex items-center justify-center">
                    <i class="ti ti-moon-stars text-[#1D9E75] text-xl"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">Total</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Kurban</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ti ti-circle-check text-green-600 text-xl"></i>
                </div>
                <span class="text-xs text-green-500 font-medium">100%</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['completed'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Selesai</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ti ti-loader text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs text-blue-500 font-medium">Proses</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['inProgress'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Sedang Berjalan</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ti ti-clock text-yellow-600 text-xl"></i>
                </div>
                <span class="text-xs text-yellow-500 font-medium">Antrian</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Menunggu</p>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @if(auth()->user()->canWrite())
        <a href="{{ route('admin.sacrifices.create') }}"
           class="bg-[#1D9E75] hover:bg-[#157a5a] text-white rounded-xl p-4 flex items-center gap-3 transition-colors">
            <i class="ti ti-plus text-2xl flex-shrink-0"></i>
            <div>
                <p class="font-semibold text-sm">Tambah Kurban</p>
                <p class="text-white/70 text-xs">Daftarkan kurban baru</p>
            </div>
        </a>
        @endif
        <a href="{{ route('admin.sacrifices.index') }}"
           class="bg-white hover:bg-gray-50 border border-gray-200 rounded-xl p-4 flex items-center gap-3 transition-colors">
            <i class="ti ti-list text-2xl text-gray-500 flex-shrink-0"></i>
            <div>
                <p class="font-semibold text-sm text-gray-800">Lihat Semua Data</p>
                <p class="text-gray-400 text-xs">Kelola seluruh data kurban</p>
            </div>
        </a>
        @if(auth()->user()->canExport())
        <a href="{{ route('admin.export') }}"
           class="bg-white hover:bg-gray-50 border border-gray-200 rounded-xl p-4 flex items-center gap-3 transition-colors">
            <i class="ti ti-table-export text-2xl text-gray-500 flex-shrink-0"></i>
            <div>
                <p class="font-semibold text-sm text-gray-800">Ekspor CSV</p>
                <p class="text-gray-400 text-xs">Unduh laporan data kurban</p>
            </div>
        </a>
        @endif
    </div>

    {{-- Recent sacrifices --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Data Kurban Terbaru</h2>
            <a href="{{ route('admin.sacrifices.index') }}" class="text-[#1D9E75] text-xs font-medium hover:underline">
                Lihat semua →
            </a>
        </div>

        @if($recentSacrifices->isEmpty())
        <div class="py-12 text-center">
            <p class="text-gray-400 text-sm">Belum ada data kurban</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3">Kode / Donatur</th>
                        <th class="px-5 py-3 hidden sm:table-cell">Hewan</th>
                        <th class="px-5 py-3">Progress</th>
                        <th class="px-5 py-3 hidden md:table-cell">Tgl Daftar</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentSacrifices as $sacrifice)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="font-mono text-xs font-semibold text-[#1D9E75]">{{ $sacrifice->reference_code }}</p>
                            <p class="text-sm text-gray-800 font-medium truncate max-w-[160px]">{{ $sacrifice->donor_name }}</p>
                        </td>
                        <td class="px-5 py-3.5 hidden sm:table-cell">
                            <span class="inline-flex items-center gap-1 text-xs font-medium bg-gray-100 text-gray-700 px-2 py-1 rounded-full">
                                {{ $sacrifice->animal_type }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-20 bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-[#1D9E75] h-full rounded-full" style="width: {{ $sacrifice->getProgressPercentage() }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $sacrifice->getProgressPercentage() }}%</span>
                            </div>
                            <span class="inline-flex items-center mt-1 text-xs font-medium px-1.5 py-0.5 rounded {{ $sacrifice->getStatusBadgeClass() }}">
                                {{ $sacrifice->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 hidden md:table-cell text-xs text-gray-500">
                            {{ $sacrifice->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.sacrifices.show', $sacrifice) }}"
                               class="text-[#1D9E75] hover:text-[#157a5a] text-xs font-medium">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
