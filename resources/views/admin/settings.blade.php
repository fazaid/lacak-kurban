@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('breadcrumb', 'Admin / Pengaturan')

@section('content')
<div class="space-y-6 pt-2 max-w-2xl">

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
        <i class="ti ti-circle-check text-green-500"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Tanda Tangan Sertifikat --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
            <i class="ti ti-signature text-[#1D9E75]"></i>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Tanda Tangan Sertifikat</h3>
                <p class="text-xs text-gray-500 mt-0.5">Upload gambar tanda tangan (PNG transparan, maks. 2MB)</p>
            </div>
        </div>

        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-6">

            {{-- Kiri --}}
            <div class="space-y-3">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Kiri — Ketua Pelaksana</p>

                @if($sigLeft)
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 flex flex-col items-center gap-3">
                    <img src="{{ route('admin.settings.signature.serve', 'left') }}"
                         class="max-h-20 object-contain opacity-80">
                    <form method="POST" action="{{ route('admin.settings.signature.delete') }}">
                        @csrf @method('DELETE')
                        <input type="hidden" name="position" value="left">
                        <button type="submit"
                                onclick="return confirm('Hapus tanda tangan kiri?')"
                                class="inline-flex items-center gap-1 text-xs text-red-500 hover:text-red-700">
                            <i class="ti ti-trash text-xs"></i> Hapus
                        </button>
                    </form>
                </div>
                @else
                <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center text-gray-400">
                    <i class="ti ti-signature text-2xl mb-1"></i>
                    <p class="text-xs">Belum ada tanda tangan</p>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.settings.signature.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="position" value="left">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        {{ $sigLeft ? 'Ganti tanda tangan' : 'Upload tanda tangan' }}
                    </label>
                    <div class="flex gap-2">
                        <input type="file" name="signature" accept="image/png,image/jpeg"
                               class="flex-1 text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#1D9E75]/10 file:text-[#1D9E75] hover:file:bg-[#1D9E75]/20">
                        <button type="submit"
                                class="shrink-0 bg-[#1D9E75] hover:bg-[#157a5a] text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                            Upload
                        </button>
                    </div>
                    @error('signature') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </form>
            </div>

            {{-- Kanan --}}
            <div class="space-y-3">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Kanan — Direktur NPC</p>

                @if($sigRight)
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 flex flex-col items-center gap-3">
                    <img src="{{ route('admin.settings.signature.serve', 'right') }}"
                         class="max-h-20 object-contain opacity-80">
                    <form method="POST" action="{{ route('admin.settings.signature.delete') }}">
                        @csrf @method('DELETE')
                        <input type="hidden" name="position" value="right">
                        <button type="submit"
                                onclick="return confirm('Hapus tanda tangan kanan?')"
                                class="inline-flex items-center gap-1 text-xs text-red-500 hover:text-red-700">
                            <i class="ti ti-trash text-xs"></i> Hapus
                        </button>
                    </form>
                </div>
                @else
                <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center text-gray-400">
                    <i class="ti ti-signature text-2xl mb-1"></i>
                    <p class="text-xs">Belum ada tanda tangan</p>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.settings.signature.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="position" value="right">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        {{ $sigRight ? 'Ganti tanda tangan' : 'Upload tanda tangan' }}
                    </label>
                    <div class="flex gap-2">
                        <input type="file" name="signature" accept="image/png,image/jpeg"
                               class="flex-1 text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#1D9E75]/10 file:text-[#1D9E75] hover:file:bg-[#1D9E75]/20">
                        <button type="submit"
                                class="shrink-0 bg-[#1D9E75] hover:bg-[#157a5a] text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                            Upload
                        </button>
                    </div>
                    @error('signature') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </form>
            </div>

        </div>

        <div class="px-5 pb-4">
            <p class="text-xs text-gray-400">
                <i class="ti ti-info-circle"></i>
                Gunakan file PNG dengan background transparan agar hasil di sertifikat lebih rapi.
                Tanda tangan akan otomatis muncul saat generate sertifikat berikutnya.
            </p>
        </div>
    </div>

</div>
@endsection
