@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('header_title', 'Pengelolaan Pengguna')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 p-4 sm:p-0">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-2">
        <div class="flex-1">
            <h1 class="text-xl sm:text-2xl font-poppins font-bold text-[var(--text-main)]">Daftar Pengguna Sistem & Aplikasi</h1>
            <p class="text-[var(--text-muted)] text-[10px] sm:text-sm font-medium mt-0.5">Manajemen akun admin website dan pelanggan aplikasi Android.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-green-500/20 hover:scale-105 transition-all">
                + Tambah Pengguna
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 p-4 sm:p-6 rounded-2xl border border-emerald-500/20 font-bold text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-500/10 text-rose-600 dark:text-rose-400 p-4 sm:p-6 rounded-2xl border border-rose-500/20 font-bold text-sm">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div class="bg-[var(--bg-card)] rounded-2xl sm:rounded-[2.5rem] shadow-[var(--card-shadow)] border border-[var(--border)] overflow-hidden transition-all duration-500">
        {{-- Desktop Table --}}
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[var(--bg-main)] border-b border-[var(--border)]">
                        <th class="px-10 py-7 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] w-1/3">Informasi Pengguna</th>
                        <th class="px-8 py-7 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em]">Hubungi</th>
                        <th class="px-8 py-7 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em]">Alamat Kirim</th>
                        <th class="px-8 py-7 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em]">Akses / Peran</th>
                        <th class="px-10 py-7 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @foreach($users as $user)
                    <tr class="hover:bg-[var(--bg-main)] transition-all group searchable-card">
                        <td class="px-10 py-8">
                            <div class="flex items-center space-x-5">
                                <div class="relative">
                                    <div class="w-12 h-12 bg-[var(--bg-main)] rounded-2xl shadow-sm border border-[var(--border)] overflow-hidden flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                        @if($user->profile_photo && file_exists(public_path('uploads/profile/' . $user->profile_photo)))
                                            <img src="{{ asset('uploads/profile/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-bold text-sm uppercase">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    @if($user->is_admin)
                                    <div class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white dark:border-slate-900 flex items-center justify-center shadow-lg" title="Administrator"></div>
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-bold text-[var(--text-main)] text-sm flex items-center">
                                        {{ $user->name }}
                                    </h4>
                                    <div class="flex flex-col text-[10px] text-[var(--text-muted)]">
                                        <span class="font-medium">{{ $user->email }}</span>
                                        <span class="mt-0.5 text-[9px] opacity-75">Terdaftar: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-8">
                            <span class="text-xs font-semibold text-[var(--text-main)]">{{ $user->phone ?: '-' }}</span>
                        </td>
                        <td class="px-8 py-8 max-w-[200px] truncate">
                            <span class="text-xs font-medium text-[var(--text-muted)]" title="{{ $user->address }}">{{ $user->address ?: '-' }}</span>
                        </td>
                        <td class="px-8 py-8">
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-colors
                                {{ $user->is_admin ? 'bg-emerald-50/50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/10 dark:border-emerald-900/30' : 'bg-blue-50/50 text-blue-600 border-blue-100 dark:bg-blue-900/10 dark:border-blue-900/30' }}">
                                <span class="w-1 h-1 rounded-full mr-2 {{ $user->is_admin ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                {{ $user->role === 'admin' ? 'Administrator' : 'Pelanggan App' }}
                            </span>
                        </td>
                        <td class="px-10 py-8">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="w-11 h-11 bg-[var(--bg-main)] text-[var(--text-muted)] hover:text-emerald-500 hover:bg-[var(--bg-card)] rounded-2xl flex items-center justify-center transition-all border border-transparent hover:border-[var(--border)] shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini dari sistem?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-11 h-11 bg-[var(--bg-main)] text-[var(--text-muted)] hover:text-red-500 hover:bg-[var(--bg-card)] rounded-2xl flex items-center justify-center transition-all border border-transparent hover:border-[var(--border)] shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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

        {{-- Mobile Card View --}}
        <div class="md:hidden divide-y divide-[var(--border)]">
            @foreach($users as $user)
            <div class="p-4 space-y-4 searchable-card">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-[var(--bg-main)] rounded-2xl border border-[var(--border)] flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                        @if($user->profile_photo && file_exists(public_path('uploads/profile/' . $user->profile_photo)))
                            <img src="{{ asset('uploads/profile/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-bold text-sm uppercase">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-[var(--text-main)] text-sm truncate">{{ $user->name }}</h4>
                        <span class="text-[9px] text-[var(--text-muted)] font-medium">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="space-y-1.5 text-xs text-[var(--text-muted)] bg-[var(--bg-main)] p-3 rounded-xl border border-[var(--border)]">
                    <div class="flex justify-between">
                        <span class="font-bold text-[9px] uppercase tracking-wider">Telepon:</span>
                        <span>{{ $user->phone ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-bold text-[9px] uppercase tracking-wider">Peran:</span>
                        <span class="font-bold {{ $user->is_admin ? 'text-emerald-500' : 'text-blue-500' }}">{{ $user->role === 'admin' ? 'Admin' : 'Pelanggan' }}</span>
                    </div>
                    @if($user->address)
                    <div class="pt-1.5 border-t border-[var(--border)] mt-1">
                        <span class="font-bold text-[9px] uppercase tracking-wider block mb-0.5">Alamat:</span>
                        <p class="text-[10px] leading-relaxed line-clamp-2">{{ $user->address }}</p>
                    </div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-[var(--bg-main)] text-xs font-bold text-[var(--text-muted)] hover:text-emerald-500 rounded-xl border border-[var(--border)] shadow-sm">
                        Edit
                    </a>
                    @if(auth()->id() !== $user->id)
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus?')" class="m-0">
                        @csrf @method('DELETE')
                        <button class="px-4 py-2 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white text-xs font-bold rounded-xl border border-rose-500/20 shadow-sm transition-colors">
                            Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
