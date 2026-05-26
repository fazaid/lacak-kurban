@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('breadcrumb', 'Admin / Manajemen User / Edit')

@section('content')
<div class="pt-2 max-w-lg space-y-4">

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
        <i class="ti ti-circle-check text-green-500"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Edit info --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
            <i class="ti ti-user-edit text-[#1D9E75]"></i>
            <h3 class="font-semibold text-gray-800 text-sm">Informasi User</h3>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-5 space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300' }}">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }}">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
                <select name="role" required
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] {{ $errors->has('role') ? 'border-red-400' : 'border-gray-300' }}">
                    <option value="staff"  {{ old('role', $user->role) === 'staff'  ? 'selected' : '' }}>Staff — tambah & edit data</option>
                    <option value="viewer" {{ old('role', $user->role) === 'viewer' ? 'selected' : '' }}>Viewer — hanya lihat</option>
                    <option value="admin"  {{ old('role', $user->role) === 'admin'  ? 'selected' : '' }}>Admin — akses penuh</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-[#1D9E75] hover:bg-[#157a5a] text-white text-sm font-semibold px-5 py-2 rounded-lg transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="border border-gray-300 text-gray-600 text-sm font-medium px-5 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Reset password --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
            <i class="ti ti-lock-password text-amber-500"></i>
            <h3 class="font-semibold text-gray-800 text-sm">Reset Password</h3>
        </div>

        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="p-5 space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password" required
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] {{ $errors->has('password') ? 'border-red-400' : 'border-gray-300' }}">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1D9E75] border-gray-300">
            </div>

            <button type="submit"
                    class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-5 py-2 rounded-lg transition-colors">
                Reset Password
            </button>
        </form>
    </div>

</div>
@endsection
