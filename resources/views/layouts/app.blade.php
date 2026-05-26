<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NPC Kurban Tracker')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.7.0/dist/tabler-icons.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/logos/logo.png') }}" sizes="32x32">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased flex flex-col">

    {{-- ── Navbar ─────────────────────────────────────────────── --}}
    <header class="bg-white/90 backdrop-blur-sm border-b border-white/20 sticky top-0 z-30 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 bg-[#1D9E75] rounded-lg flex items-center justify-center group-hover:bg-[#157a5a] transition-colors flex-shrink-0">
                    <img src="{{ asset('images/logos/logo.png') }}"
                     alt="NPC Logo"
                     class="h-8 w-auto">
                </div>
                <div class="leading-none">
                    <span class="font-bold text-gray-800 text-sm">NPC</span>
                    <span class="font-normal text-gray-500 text-sm hidden sm:inline"> Kurban Tracker</span>
                </div>
            </a>

            {{-- Nav actions --}}
            <nav class="flex items-center gap-2">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 rounded-lg
                          {{ request()->routeIs('home') ? 'text-[#1D9E75] bg-[#1D9E75]/10' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-100' }}
                          transition-colors">
                    <i class="ti ti-search text-base"></i>
                    <span class="hidden sm:inline">Cek Status</span>
                </a>
                @auth
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors">
                    <i class="ti ti-layout-dashboard text-base"></i>
                    <span class="hidden sm:inline">Admin</span>
                </a>
                @else
                <a href="{{ route('admin.login') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors">
                    <i class="ti ti-lock text-base"></i>
                    <span class="hidden sm:inline">Admin</span>
                </a>
                @endauth
            </nav>

        </div>
    </header>

    {{-- ── Global flash messages ──────────────────────────────── --}}
    @if(session('success') || session('error') || session('info'))
    <div class="max-w-5xl mx-auto w-full px-4 sm:px-6 pt-4">
        @if(session('success'))
        <div role="alert" class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm mb-2">
            <i class="ti ti-circle-check text-green-600 flex-shrink-0 mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div role="alert" class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm mb-2">
            <i class="ti ti-alert-circle text-red-600 flex-shrink-0 mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if(session('info'))
        <div role="alert" class="flex items-start gap-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-4 py-3 text-sm mb-2">
            <i class="ti ti-info-circle text-blue-600 flex-shrink-0 mt-0.5"></i>           <span>{{ session('info') }}</span>
        </div>
        @endif
    </div>
    @endif

    {{-- ── Page content ───────────────────────────────────────── --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ── Footer ─────────────────────────────────────────────── --}}
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">

                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 bg-[#1D9E75] rounded-md flex items-center justify-center flex-shrink-0">
                            <img src="{{ asset('images/logos/logo.png') }}"
                     alt="NPC - Nusantara Palestina Center"
                     class="h-6 w-auto drop-shadow-lg">
                        </div>
                        <span class="font-bold text-gray-800 text-sm">NPC Kurban Tracker</span>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Platform pelacakan kurban yang transparan dan terpercaya. Pantau setiap tahapan kurban Anda secara real-time.
                    </p>
                </div>

                {{-- Links --}}
                <div>
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-3">Layanan</p>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-[#1D9E75] transition-colors flex items-center gap-1.5">
                                <i class="ti ti-search text-xs"></i> Lacak Kurban
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.login') }}" class="text-xs text-gray-500 hover:text-[#1D9E75] transition-colors flex items-center gap-1.5">
                                <i class="ti ti-lock text-xs"></i> Panel Admin
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-3">Kontak</p>
                    <ul class="space-y-1.5">
                        <li class="flex items-center gap-1.5 text-xs text-gray-500">
                            <i class="ti ti-mail text-[#1D9E75] flex-shrink-0"></i>
                            <a href="mailto:info@npc.id" class="hover:text-[#1D9E75] transition-colors">info@npc.id</a>
                        </li>
                        <li class="flex items-center gap-1.5 text-xs text-gray-500">
                            <i class="ti ti-phone text-[#1D9E75] flex-shrink-0"></i>
                            <a href="tel:021-87788187" class="hover:text-[#1D9E75] transition-colors">+62 21-87788187</a>
                        </li>
                        <li class="flex items-center gap-1.5 text-xs text-gray-500">
                            <i class="ti ti-brand-whatsapp text-[#1D9E75] flex-shrink-0"></i>
                            <a href="https://wa.me/6281119119898" class="hover:text-[#1D9E75] transition-colors" target="_blank" rel="noopener">WhatsApp</a>
                        </li>
                        <li class="flex items-start gap-1.5 text-xs text-gray-500">
                            <i class="ti ti-map-pin text-[#1D9E75] flex-shrink-0 mt-0.5"></i>
                            <span>Jl. Bina Marga No. 25 C99 Business Park, Ceger, Cipayung, Jakarta Timur, DKI Jakarta 13820</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} NPC Kurban Tracker. Semua hak dilindungi.
                </p>
                <p class="text-xs text-gray-400 flex items-center gap-1">
                    <i class="ti ti-heart-filled text-red-400 text-xs"></i>
                    Amanah &amp; Transparan
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
