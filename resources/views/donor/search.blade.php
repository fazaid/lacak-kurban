@extends('layouts.app')

@section('title', 'Lacak Kurban Anda — NPC Kurban Tracker')

@section('content')

{{-- ── Hero ──────────────────────────────────────────────────── --}}
<section class="bg-gradient-to-br from-[#1D9E75] via-[#1a8d68] to-[#157a5a] px-4 py-16 sm:py-24">
    <div class="max-w-lg mx-auto text-center">

        {{-- Brand mark --}}
        <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-5">
            <i class="ti ti-moon-stars text-white text-3xl" aria-hidden="true"></i>
        </div>
        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Lacak Kurban Anda</h1>
        <p class="text-white/80 text-base sm:text-lg mb-10 max-w-sm mx-auto">
            Pantau setiap tahapan pelaksanaan kurban secara transparan dan real-time.
        </p>

        {{-- Search card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-left">
            <h2 class="text-base font-bold text-gray-800 mb-1">Cek Status Kurban</h2>
            <p class="text-sm text-gray-500 mb-5">Masukkan kode referensi yang Anda terima dari panitia.</p>

            @if(session('error'))
            <div role="alert" class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-4">
                <i class="ti ti-alert-circle text-red-500 flex-shrink-0 mt-0.5" aria-hidden="true"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form action="{{ route('sacrifice.search') }}" method="POST" novalidate>
                @csrf
                <div class="mb-4">
                    <label for="reference_code" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Kode Referensi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ti ti-hash text-gray-400" aria-hidden="true"></i>
                        </div>
                        <input
                            type="text"
                            id="reference_code"
                            name="reference_code"
                            value="{{ old('reference_code') }}"
                            placeholder="NPC-2024-001"
                            autocomplete="off"
                            spellcheck="false"
                            aria-describedby="ref-hint"
                            class="w-full pl-10 pr-4 py-3 border rounded-xl text-sm text-gray-800 font-mono tracking-wide uppercase
                                   placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-[#1D9E75] focus:border-transparent transition
                                   @error('reference_code') border-red-400 bg-red-50 @else border-gray-300 @enderror"
                        >
                    </div>
                    @error('reference_code')
                        <p class="text-red-500 text-xs mt-1" role="alert">{{ $message }}</p>
                    @else
                        <p id="ref-hint" class="text-gray-400 text-xs mt-1">Format: NPC-TAHUN-NOMOR (contoh: NPC-2024-001)</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-[#1D9E75] hover:bg-[#157a5a] active:scale-[.98] text-white font-semibold py-3 px-4 rounded-xl transition-all flex items-center justify-center gap-2">
                    <i class="ti ti-search" aria-hidden="true"></i>
                    Lacak Sekarang
                </button>
            </form>
        </div>

    </div>
</section>

{{-- ── Feature cards ──────────────────────────────────────────── --}}
<section class="bg-gray-50 px-4 py-12" aria-label="Keunggulan layanan">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-center text-lg font-bold text-gray-800 mb-8">Mengapa NPC Kurban Tracker?</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

            <article class="bg-white rounded-2xl border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-[#1D9E75]/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-shield-check text-[#1D9E75] text-2xl" aria-hidden="true"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Transparan</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Setiap tahapan kurban didokumentasikan dan dapat diakses kapan saja oleh donatur.
                </p>
            </article>

            <article class="bg-white rounded-2xl border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-[#1D9E75]/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-clock text-[#1D9E75] text-2xl" aria-hidden="true"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Real-Time</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Status kurban diperbarui langsung oleh panitia, sehingga informasi selalu akurat.
                </p>
            </article>

            <article class="bg-white rounded-2xl border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-[#1D9E75]/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-certificate text-[#1D9E75] text-2xl" aria-hidden="true"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Bersertifikat</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Donatur menerima sertifikat resmi kurban yang dapat diunduh setelah pelaksanaan selesai.
                </p>
            </article>

        </div>
    </div>
</section>

{{-- ── FAQ Accordion ───────────────────────────────────────────── --}}
<section class="bg-white border-t border-gray-100 px-4 py-12" aria-label="Pertanyaan umum">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-center text-lg font-bold text-gray-800 mb-2">Pertanyaan yang Sering Ditanyakan</h2>
        <p class="text-center text-sm text-gray-500 mb-8">Ada pertanyaan? Kami siap membantu.</p>

        <div class="space-y-3" id="faq-list">

            @foreach([
                [
                    'q' => 'Dimana saya menemukan kode referensi kurban saya?',
                    'a' => 'Kode referensi kurban (format NPC-TAHUN-XXX) tercantum pada bukti pembayaran atau konfirmasi pendaftaran yang dikirimkan melalui email atau WhatsApp setelah Anda mendaftar.'
                ],
                [
                    'q' => 'Berapa lama proses kurban berlangsung?',
                    'a' => 'Proses kurban meliputi 4 tahap: pembelian hewan, penyembelihan, distribusi daging, dan laporan akhir. Seluruh proses biasanya berlangsung selama 1–3 hari sekitar hari raya Idul Adha.'
                ],
                [
                    'q' => 'Bagaimana cara mengetahui kurban saya sudah disembelih?',
                    'a' => 'Cukup masukkan kode referensi Anda di kolom pencarian. Halaman detail akan menampilkan status setiap tahapan beserta tanggal penyelesaiannya secara real-time.'
                ],
                [
                    'q' => 'Apakah saya bisa mengunduh sertifikat kurban?',
                    'a' => 'Ya. Setelah seluruh tahapan selesai, sertifikat kurban resmi akan tersedia dan dapat diunduh dalam format PDF melalui tab "Sertifikat" pada halaman detail kurban Anda.'
                ],
                [
                    'q' => 'Apa yang harus dilakukan jika kode referensi tidak ditemukan?',
                    'a' => 'Pastikan kode diketik dengan benar (huruf besar, format NPC-TAHUN-XXX). Jika masih tidak ditemukan, hubungi panitia melalui email info@npc.id atau WhatsApp kami.'
                ],
            ] as $i => $item)
            <div class="border border-gray-200 rounded-xl overflow-hidden" x-data="{ open: false }">
                <button
                    type="button"
                    onclick="toggleFaq({{ $i }})"
                    aria-expanded="false"
                    aria-controls="faq-answer-{{ $i }}"
                    id="faq-btn-{{ $i }}"
                    class="w-full flex items-center justify-between px-5 py-4 text-left text-sm font-semibold text-gray-800 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#1D9E75]"
                >
                    <span>{{ $item['q'] }}</span>
                    <i class="ti ti-chevron-down text-gray-400 flex-shrink-0 ml-3 transition-transform duration-200 faq-icon" aria-hidden="true"></i>
                </button>
                <div
                    id="faq-answer-{{ $i }}"
                    role="region"
                    aria-labelledby="faq-btn-{{ $i }}"
                    class="hidden px-5 pb-4"
                >
                    <p class="text-sm text-gray-600 leading-relaxed pt-1 border-t border-gray-100">{{ $item['a'] }}</p>
                </div>
            </div>
            @endforeach

        </div>

        <p class="text-center text-sm text-gray-500 mt-8">
            Masih ada pertanyaan?
            <a href="mailto:info@npc.id" class="text-[#1D9E75] font-medium hover:underline">Hubungi kami</a>
        </p>
    </div>
</section>

@push('scripts')
<script>
function toggleFaq(index) {
    const btn    = document.getElementById('faq-btn-' + index);
    const answer = document.getElementById('faq-answer-' + index);
    const icon   = btn.querySelector('.faq-icon');
    const isOpen = !answer.classList.contains('hidden');

    // Close all others
    document.querySelectorAll('[id^="faq-answer-"]').forEach((el, i) => {
        el.classList.add('hidden');
        const b = document.getElementById('faq-btn-' + i);
        b?.setAttribute('aria-expanded', 'false');
        b?.querySelector('.faq-icon')?.classList.remove('rotate-180');
    });

    if (!isOpen) {
        answer.classList.remove('hidden');
        btn.setAttribute('aria-expanded', 'true');
        icon.classList.add('rotate-180');
    }
}
</script>
@endpush

@endsection
