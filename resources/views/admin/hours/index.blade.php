@extends('layouts.admin')

@section('title', 'Jadwal Operasional')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 animate-fade-in">

    <!-- HEADER PROFILE -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 px-2 sm:px-4">
        <div class="space-y-1 sm:space-y-2">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-1.5 h-6 sm:h-8 bg-emerald-500 rounded-full"></div>
                <h2 class="text-2xl sm:text-4xl font-black text-[var(--text-main)] tracking-tight">Jam Operasional</h2>
            </div>
            <p class="text-[10px] sm:text-sm text-[var(--text-muted)] font-medium ml-3.5 sm:ml-4">Atur ketersediaan layanan katering.</p>
        </div>
        
        @if(session('success'))
        <div class="flex items-center gap-3 px-6 py-3 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-500 text-xs font-bold animate-bounce-short">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
            Jadwal Berhasil Diperbarui
        </div>
        @endif
    </div>

    <!-- GRID LAYOUT -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6 px-2">
        @foreach($hours as $hour)
            @php $isClosed = $hour->is_closed; @endphp
            <div class="group relative bg-[var(--bg-card)] border border-[var(--border)] rounded-2xl sm:rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:border-emerald-500/30 hover:shadow-2xl hover:shadow-emerald-500/10 {{ $isClosed ? 'opacity-70' : '' }} shadow-[var(--card-shadow)]">
                
                {{-- Background Decor --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 blur-[50px] rounded-full group-hover:bg-emerald-500/10 transition-colors"></div>
                
                <form action="{{ route('admin.hours.update', $hour) }}" method="POST" class="relative z-10 p-3 sm:p-8 space-y-3 sm:space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Header Card --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm sm:text-2xl font-black text-[var(--text-main)] tracking-tighter leading-none">{{ $hour->day_name }}</h3>
                            <p class="text-[7px] sm:text-[10px] font-black {{ $isClosed ? 'text-rose-500' : 'text-emerald-500' }} uppercase tracking-widest mt-0.5 sm:mt-1">
                                {{ $isClosed ? 'Tutup' : 'Buka' }}
                            </p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer group/toggle shrink-0">
                            <input type="checkbox" name="is_closed" onchange="this.form.submit()" class="sr-only peer" {{ $isClosed ? 'checked' : '' }}>
                            <div class="w-8 h-4 sm:w-14 sm:h-7 bg-[var(--bg-main)] border border-[var(--border)] rounded-full peer peer-checked:bg-rose-500/20 peer-checked:border-rose-500/30 after:content-[''] after:absolute after:top-[2px] sm:after:top-[4px] after:left-[2px] sm:after:left-[4px] after:bg-slate-400 after:rounded-full after:h-3 sm:after:h-5 after:w-3 sm:after:w-5 after:transition-all peer-checked:after:translate-x-4 sm:peer-checked:after:translate-x-7 peer-checked:after:bg-rose-500 shadow-inner"></div>
                        </label>
                    </div>

                    {{-- Inputs Section --}}
                    <div class="space-y-2 sm:space-y-4 pt-1 sm:pt-2 transition-all duration-500 {{ $isClosed ? 'grayscale blur-[1px] pointer-events-none' : '' }}">
                        <div class="space-y-1 sm:space-y-2">
                            <label class="flex items-center gap-1 sm:gap-2 text-[8px] sm:text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest ml-1">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Jam Buka
                            </label>
                            <input type="time" name="open_time" value="{{ $hour->open_time }}"
                                class="w-full bg-[var(--bg-main)] border border-[var(--border)] px-2.5 py-1.5 sm:px-6 sm:py-4 rounded-lg sm:rounded-2xl text-[11px] sm:text-base font-bold text-[var(--text-main)] outline-none focus:ring-2 ring-emerald-500/50 transition-all">
                        </div>
                        
                        <div class="space-y-1 sm:space-y-2">
                            <label class="flex items-center gap-1 sm:gap-2 text-[8px] sm:text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest ml-1">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Jam Tutup
                            </label>
                            <input type="time" name="close_time" value="{{ $hour->close_time }}"
                                class="w-full bg-[var(--bg-main)] border border-[var(--border)] px-2.5 py-1.5 sm:px-6 sm:py-4 rounded-lg sm:rounded-2xl text-[11px] sm:text-base font-bold text-[var(--text-main)] outline-none focus:ring-2 ring-emerald-500/50 transition-all">
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="pt-0.5 sm:pt-2">
                        <button type="submit" 
                            class="w-full py-1.5 sm:py-4 rounded-lg sm:rounded-2xl text-[8px] sm:text-xs font-black uppercase tracking-[0.1em] sm:tracking-[0.2em] transition-all duration-300 flex items-center justify-center gap-1 sm:gap-3
                            {{ $isClosed ? 'bg-[var(--bg-main)] text-[var(--text-muted)] border border-[var(--border)]' : 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-lg sm:shadow-xl shadow-emerald-500/20 active:scale-95' }}">
                            @if(!$isClosed)
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            @endif
                            {{ $isClosed ? 'Tutup' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>

    <!-- FOOTER TIPS -->
    <div class="mx-4 p-8 bg-gradient-to-r from-emerald-500/5 to-transparent border border-emerald-500/10 rounded-[2.5rem] flex flex-col md:flex-row items-center gap-6 group">
        <div class="w-16 h-16 bg-emerald-500/10 rounded-3xl flex items-center justify-center text-3xl group-hover:scale-110 transition duration-500">
            💡
        </div>
        <div class="flex-1 space-y-1 text-center md:text-left">
            <h4 class="text-[var(--text-main)] font-bold text-lg">Tips Pengaturan</h4>
            <p class="text-[var(--text-muted)] text-sm leading-relaxed max-w-3xl">
                Gunakan <span class="text-emerald-500 font-bold">Toggle Switch</span> di pojok kanan atas kartu untuk menandai hari libur secara instan. Pastikan untuk menekan <span class="text-emerald-500 font-bold">Update Jadwal</span> setelah mengubah waktu operasional agar tersimpan secara permanen.
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-short {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }
    .animate-bounce-short {
        animation: bounce-short 2s infinite;
    }
</style>
@endsection