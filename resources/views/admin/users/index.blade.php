@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('breadcrumb', 'Admin / Manajemen User')

@section('header-actions')
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 bg-[#1D9E75] hover:bg-[#157a5a] text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
        <i class="ti ti-plus"></i>
        Tambah User
    </a>
@endsection

@section('content')
<div class="space-y-4 pt-2">

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
        <i class="ti ti-circle-check text-green-500"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->has('delete'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
        <i class="ti ti-alert-circle text-red-500"></i>
        {{ $errors->first('delete') }}
    </div>
    @endif

    {{-- Role legend --}}
    <div class="flex flex-wrap gap-3 text-xs text-gray-500">
        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-[#1D9E75] inline-block"></span> Admin — akses penuh</span>
        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span> Staff — tambah & edit data</span>
        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-gray-400 inline-block"></span> Viewer — hanya lihat</span>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3">Nama / Email</th>
                    <th class="px-5 py-3">Role</th>
                    <th class="px-5 py-3 hidden md:table-cell">Bergabung</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors {{ $user->id === auth()->id() ? 'bg-[#1D9E75]/5' : '' }}">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#1D9E75]/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-[#1D9E75]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                    <span class="text-xs text-[#1D9E75] font-normal">(Anda)</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full {{ $user->roleBadgeClass() }}">
                            {{ $user->roleLabel() }}
                        </span>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell text-xs text-gray-500">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               title="Edit"
                               class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-blue-500 hover:text-white text-gray-600 flex items-center justify-center transition-colors">
                                <i class="ti ti-edit text-sm"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                @csrf @method('DELETE')
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

    <p class="text-xs text-gray-400">Total {{ $users->count() }} user terdaftar.</p>

</div>
@endsection
