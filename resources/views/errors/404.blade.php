@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan — NPC Kurban Tracker')

@section('content')
<div class="flex flex-col items-center justify-center py-24 px-4 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="text-center max-w-md">

        <div class="inline-flex items-center justify-center w-20 h-20 bg-[#1D9E75]/10 rounded-full mb-6">
            <i class="ti ti-map-pin-off text-[#1D9E75] text-4xl" aria-hidden="true"></i>
        </div>

        <p class="text-7xl font-black text-gray-200 mb-2" aria-hidden="true">404</p>
        <h1 class="text-xl font-bold text-gray-800 mb-3">Kurban Tidak Ditemukan</h1>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            Kode referensi kurban tidak ditemukan atau halaman yang Anda cari tidak tersedia.
            Periksa kembali kode Anda dan coba lagi.
        </p>

        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-8 text-left">
            <p class="text-xs font-semibold text-gray-600 mb-2">Coba lacak lagi:</p>
            <form action="{{ route('sacrifice.search') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="reference_code"
                       placeholder="NPC-2024-001"
                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-[#1D9E75]">
                <button type="submit"
                        class="bg-[#1D9E75] hover:bg-[#157a5a] text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <i class="ti ti-search" aria-hidden="true"></i>
                </button>
            </form>
        </div>

        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#1D9E75] transition-colors">
            <i class="ti ti-arrow-left text-xs" aria-hidden="true"></i>
            Kembali ke halaman utama
        </a>
    </div>
</div>
@endsection
