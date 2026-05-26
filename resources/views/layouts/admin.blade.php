<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — NPC Kurban Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.7.0/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col flex-shrink-0 transition-transform duration-200 z-40 fixed inset-y-0 left-0 lg:static lg:translate-x-0 -translate-x-full">
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200">
                <div class="w-9 h-9 bg-[#1D9E75] rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-moon-stars text-white text-lg"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-800 text-sm leading-tight">NPC Kurban</p>
                    <p class="text-xs text-gray-500">Tracker System</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 overflow-y-auto">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#1D9E75] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="ti ti-dashboard text-lg"></i>
                    Dashboard
                </a>

                <a href="{{ route('admin.sacrifices.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm font-medium transition-colors {{ request()->routeIs('admin.sacrifices.*') ? 'bg-[#1D9E75] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="ti ti-list text-lg"></i>
                    Data Kurban
                </a>

                @if(auth()->user()->canWrite())
                <a href="{{ route('admin.sacrifices.create') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm font-medium transition-colors {{ request()->routeIs('admin.sacrifices.create') ? 'bg-[#1D9E75] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="ti ti-plus text-lg"></i>
                    Tambah Kurban
                </a>
                @endif

                <a href="{{ route('admin.statistics') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm font-medium transition-colors {{ request()->routeIs('admin.statistics') ? 'bg-[#1D9E75] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="ti ti-chart-bar text-lg"></i>
                    Statistik
                </a>

                <div class="mt-4 mb-2">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Lainnya</p>
                </div>

                @if(auth()->user()->canExport())
                <a href="{{ route('admin.export') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                    <i class="ti ti-download text-lg"></i>
                    Ekspor CSV
                </a>
                @endif

                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                    <i class="ti ti-external-link text-lg"></i>
                    Portal Donatur
                </a>

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.settings') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm font-medium transition-colors {{ request()->routeIs('admin.settings*') ? 'bg-[#1D9E75] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="ti ti-settings text-lg"></i>
                    Pengaturan
                </a>
                @endif
            </nav>

            {{-- User info --}}
            <div class="px-4 py-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-[#1D9E75]/10 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="ti ti-user text-[#1D9E75] text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            <span class="text-xs font-semibold px-1.5 py-0.5 rounded {{ auth()->user()->roleBadgeClass() }} flex-shrink-0">
                                {{ auth()->user()->roleLabel() }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" title="Logout"
                                class="text-gray-400 hover:text-red-500 transition-colors">
                            <i class="ti ti-logout text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top bar --}}
            <header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-3 flex items-center gap-4">
                <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="ti ti-menu-2 text-xl"></i>
                </button>

                <div class="flex-1">
                    <h1 class="text-base font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('breadcrumb')
                        <div class="text-xs text-gray-500 mt-0.5">@yield('breadcrumb')</div>
                    @endif
                </div>

                @hasSection('header-actions')
                    <div class="flex items-center gap-2">
                        @yield('header-actions')
                    </div>
                @endif
            </header>

            {{-- Flash messages --}}
            <div class="px-4 lg:px-6 pt-4">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm flex items-start gap-2 mb-4">
                        <i class="ti ti-circle-check text-green-600 text-base flex-shrink-0 mt-0.5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm flex items-start gap-2 mb-4">
                        <i class="ti ti-alert-circle text-red-600 text-base flex-shrink-0 mt-0.5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto px-4 lg:px-6 pb-6">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Sidebar overlay for mobile --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggle = document.getElementById('sidebar-toggle');

        toggle?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>

    @stack('scripts')
</body>
</html>
