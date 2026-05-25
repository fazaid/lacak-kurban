<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — NPC Kurban Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.7.0/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-[#1D9E75] to-[#157a5a] flex items-center justify-center p-4 font-sans antialiased">
    <div class="w-full max-w-sm">
        {{-- Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-white/20 rounded-2xl mb-3">
                <i class="ti ti-moon-stars text-white text-2xl"></i>
            </div>
            <h1 class="text-xl font-bold text-white">NPC Kurban Tracker</h1>
            <p class="text-white/70 text-sm mt-1">Panel Administrasi</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Masuk ke Akun Anda</h2>

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm flex items-start gap-2 mb-4">
                    <i class="ti ti-alert-circle flex-shrink-0 mt-0.5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ti ti-mail text-gray-400"></i>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="admin@npc.id"
                            autocomplete="email"
                            class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] focus:border-transparent transition
                                   @error('email') border-red-400 bg-red-50 @else border-gray-300 @enderror"
                        >
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ti ti-lock text-gray-400"></i>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] focus:border-transparent transition
                                   @error('password') border-red-400 bg-red-50 @else border-gray-300 @enderror"
                        >
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="w-4 h-4 rounded border-gray-300 text-[#1D9E75] accent-[#1D9E75] cursor-pointer"
                        >
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-[#1D9E75] hover:bg-[#157a5a] text-white font-semibold py-2.5 px-4 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="ti ti-login"></i>
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-white/50 text-xs mt-6">
            &copy; {{ date('Y') }} NPC Kurban Tracker
        </p>
    </div>
</body>
</html>
