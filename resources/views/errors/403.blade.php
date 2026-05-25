@extends('layouts.app')

@section('title', 'Akses Ditolak — NPC Kurban Tracker')

@section('content')
<div class="flex flex-col items-center justify-center py-24 px-4 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="text-center max-w-md">

        <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6">
            <i class="ti ti-lock text-red-500 text-4xl" aria-hidden="true"></i>
        </div>

        <p class="text-7xl font-black text-gray-200 mb-2" aria-hidden="true">403</p>
        <h1 class="text-xl font-bold text-gray-800 mb-3">Akses Ditolak</h1>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            Anda tidak memiliki izin untuk melakukan tindakan ini.
            Hubungi administrator jika Anda merasa ini adalah kesalahan.
        </p>

        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#1D9E75] transition-colors">
            <i class="ti ti-arrow-left text-xs" aria-hidden="true"></i>
            Kembali ke halaman sebelumnya
        </a>
    </div>
</div>
@endsection
