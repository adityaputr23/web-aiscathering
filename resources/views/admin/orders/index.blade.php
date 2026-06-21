@extends('layouts.admin')

@section('title', 'Kelola Pesanan')
@section('header_title', 'Kelola Pesanan')

@section('content')
<div class="space-y-8 animate-fade-in p-4 sm:p-0">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 sm:gap-6 px-2">
        <div class="flex-1">
            <h2 class="text-xl sm:text-2xl font-black text-[var(--text-main)] tracking-tight">Daftar Pesanan</h2>
            <p class="text-[10px] sm:text-sm text-[var(--text-muted)] font-medium mt-0.5">Kelola status pesanan.</p>
        </div>
        <button onclick="openSyncModal()" class="flex items-center gap-2 text-[8px] sm:text-[10px] font-black uppercase tracking-widest text-white bg-blue-500 hover:bg-blue-600 px-4 py-2.5 rounded-full border border-transparent shadow-[0_4px_12px_rgba(59,130,246,0.3)] hover:scale-105 transition-all duration-300 w-fit cursor-pointer">
            <span class="text-xs font-bold">+</span>
            Add Android Sync
        </button>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-6 py-4 rounded-3xl font-bold text-sm flex items-center gap-3">
        <span>✅</span> {{ session('success') }}
    </div>
    @endif

    <div class="bg-[var(--bg-card)] border border-[var(--border)] rounded-2xl sm:rounded-[3rem] overflow-hidden shadow-[var(--card-shadow)]">
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[var(--border)] bg-[var(--bg-main)]/50">
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest">ID / Waktu</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest">Pelanggan</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest">Menu Pesanan</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest">Total Harga</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-[var(--text-muted)] uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($orders as $order)
                    <tr class="group hover:bg-[var(--bg-main)]/30 transition-colors">
                        <td class="px-8 py-6">
                            <p class="text-xs font-black text-[var(--text-main)] mb-1">#{{ $order->order_id ?? 'ORD-'.$order->id }}</p>
                            <p class="text-[10px] text-[var(--text-muted)] font-medium">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-sm font-bold text-[var(--text-main)]">{{ $order->customer_name ?? 'Pelanggan' }}</p>
                            <p class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $order->user_email }}</p>
                            @if($order->customer_phone)
                            <p class="text-[9px] text-[var(--text-muted)] font-semibold">{{ $order->customer_phone }}</p>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="max-w-xs">
                                <p class="text-sm font-bold text-[var(--text-main)] truncate" title="{{ $order->items_title }}">{{ $order->items_title }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm font-black text-[var(--text-main)]">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center">
                                <select onchange="handleStatusChange(this, {{ $order->id }}, '{{ $order->status }}')" 
                                    class="text-[10px] font-black px-4 py-2 rounded-xl border border-[var(--border)] bg-[var(--bg-main)] text-[var(--text-main)] outline-none cursor-pointer focus:ring-2 ring-emerald-500/20 transition-all
                                    {{ in_array($order->status, ['Selesai','COMPLETED']) ? 'text-emerald-500' :
                                       ($order->status == 'Dikirim' ? 'text-orange-500' :
                                       (in_array($order->status, ['Diproses','PROCESSING','Diterima']) ? 'text-blue-500' : 
                                       (in_array($order->status, ['Dibatalkan','CANCELLED','Ditolak']) ? 'text-rose-500' : 'text-yellow-500'))) }}">
                                    <option value="Pending" {{ in_array($order->status, ['Pending','PENDING']) ? 'selected' : '' }}>⏰ Pending</option>
                                    <option value="Diproses" {{ in_array($order->status, ['Diproses','PROCESSING','Diterima']) ? 'selected' : '' }}>⏳ Diproses</option>
                                    <option value="Dikirim" {{ in_array($order->status, ['Dikirim','SHIPPED']) ? 'selected' : '' }}>🚗 Dikirim</option>
                                    <option value="Selesai" {{ in_array($order->status, ['Selesai','COMPLETED']) ? 'selected' : '' }}>✅ Selesai</option>
                                    <option value="Dibatalkan" {{ in_array($order->status, ['Dibatalkan','CANCELLED','Ditolak']) ? 'selected' : '' }}>🚫 Batal</option>
                                </select>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" onclick="viewOrderDetails({{ json_encode($order) }})" class="w-10 h-10 bg-blue-500/10 text-blue-500 border border-blue-500/20 rounded-xl hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus data pesanan ini?')" class="inline-block m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 bg-rose-500/10 text-rose-500 border border-rose-500/20 rounded-xl hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-[var(--bg-main)] rounded-full flex items-center justify-center text-4xl grayscale opacity-30">📦</div>
                                <p class="text-[var(--text-muted)] font-medium">Belum ada pesanan yang masuk dari aplikasi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View -->
        <div class="md:hidden divide-y divide-[var(--border)]">
            @forelse($orders as $order)
            <div class="p-4 space-y-3 searchable-card">
                <div class="flex justify-between items-start">
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-black text-[var(--text-main)]">#{{ $order->order_id ?? 'ORD-'.$order->id }}</p>
                        <p class="text-[9px] text-[var(--text-muted)] font-medium mt-0.5">{{ \Carbon\Carbon::parse($order->created_at)->format('d M, H:i') }}</p>
                        <p class="text-xs font-bold text-[var(--text-main)] mt-1 truncate">{{ $order->customer_name ?? 'Pelanggan' }}</p>
                        <p class="text-[10px] text-[var(--text-muted)] mt-0.5 truncate">{{ $order->user_email }}</p>
                    </div>
                    <div class="flex gap-1.5 shrink-0 ml-4">
                        <button onclick="viewOrderDetails({{ json_encode($order) }})" class="w-8 h-8 bg-blue-500/10 text-blue-500 rounded-lg flex items-center justify-center border border-blue-500/20" title="Detail">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus?')" class="m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 bg-rose-500/10 text-rose-500 rounded-lg flex items-center justify-center border border-rose-500/20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div>
                    <p class="text-[11px] text-[var(--text-muted)] line-clamp-1 italic">{{ $order->items_title }}</p>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <p class="text-[13px] font-black text-emerald-500">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <div class="m-0">
                        <select onchange="handleStatusChange(this, {{ $order->id }}, '{{ $order->status }}')" 
                            class="text-[9px] font-black px-2.5 py-1.5 rounded-lg border border-[var(--border)] bg-[var(--bg-main)] text-[var(--text-main)] outline-none
                            {{ in_array($order->status, ['Selesai','COMPLETED']) ? 'text-emerald-500' :
                                ($order->status == 'Dikirim' ? 'text-orange-500' :
                                (in_array($order->status, ['Diproses','PROCESSING','Diterima']) ? 'text-blue-500' : 
                                (in_array($order->status, ['Dibatalkan','CANCELLED','Ditolak']) ? 'text-rose-500' : 'text-yellow-500'))) }}">
                            <option value="Pending" {{ in_array($order->status, ['Pending','PENDING']) ? 'selected' : '' }}>Pending</option>
                            <option value="Diproses" {{ in_array($order->status, ['Diproses','PROCESSING','Diterima']) ? 'selected' : '' }}>Proses</option>
                            <option value="Dikirim" {{ in_array($order->status, ['Dikirim','SHIPPED']) ? 'selected' : '' }}>Kirim</option>
                            <option value="Selesai" {{ in_array($order->status, ['Selesai','COMPLETED']) ? 'selected' : '' }}>Selesai</option>
                            <option value="Dibatalkan" {{ in_array($order->status, ['Dibatalkan','CANCELLED','Ditolak']) ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-10 text-center">
                <p class="text-[var(--text-muted)] text-xs font-medium">Belum ada pesanan.</p>
            </div>
            @endforelse
        </div>
        
        @if($orders->hasPages())
        <div class="px-8 py-6 bg-[var(--bg-main)]/30 border-t border-[var(--border)]">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<!-- ORDER DETAILS MODAL -->
<div id="order-details-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm hidden animate-fade-in">
    <div class="bg-[var(--bg-card)] border border-[var(--border)] w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-[2rem] sm:rounded-[3rem] p-6 sm:p-10 shadow-2xl relative">
        <button onclick="closeDetailsModal()" class="absolute top-6 right-6 w-10 h-10 bg-[var(--bg-main)] border border-[var(--border)] rounded-full flex items-center justify-center text-[var(--text-main)] hover:text-rose-500 transition-colors">
            ✕
        </button>

        <div class="space-y-6 text-left">
            <!-- Header -->
            <div class="border-b border-[var(--border)] pb-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span id="modal-order-emoji" class="text-3xl">📦</span>
                            <h3 class="text-2xl font-black text-[var(--text-main)] tracking-tight" id="modal-order-id">#ORD-0</h3>
                        </div>
                        <p class="text-xs text-[var(--text-muted)] mt-1.5 font-bold" id="modal-order-date">Tanggal Pesanan: -</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <select id="modal-order-status-select" onchange="modalStatusChanged(this)" class="text-xs font-black px-4 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg-main)] text-[var(--text-main)] outline-none cursor-pointer">
                            <option value="Pending">⏰ Pending</option>
                            <option value="Diproses">⏳ Diproses</option>
                            <option value="Dikirim">🚗 Dikirim</option>
                            <option value="Selesai">✅ Selesai</option>
                            <option value="Dibatalkan">🚫 Batal</option>
                        </select>
                        <span id="modal-order-payment-status" class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border">UNPAID</span>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column: Customer details -->
                <div class="space-y-4 bg-[var(--bg-main)]/50 p-6 rounded-2xl border border-[var(--border)]">
                    <h4 class="text-xs font-black uppercase tracking-widest text-blue-500">Pelanggan & Pengiriman</h4>
                    <div class="space-y-3 text-xs sm:text-sm">
                        <div>
                            <p class="text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider">Nama Pelanggan</p>
                            <p class="font-bold text-[var(--text-main)]" id="modal-customer-name">-</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider">Kontak</p>
                            <p class="font-semibold text-[var(--text-main)]" id="modal-customer-contact">-</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider">Alamat Pengiriman</p>
                            <p class="font-medium text-[var(--text-main)] leading-relaxed" id="modal-shipping-address">-</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider">Tanggal & Waktu Pengiriman</p>
                            <p class="font-bold text-orange-500" id="modal-delivery-date">-</p>
                        </div>
                        <div id="modal-cancel-reason-container" class="hidden p-4 bg-rose-500/10 border border-rose-500/20 text-rose-500 rounded-xl">
                            <p class="text-[9px] font-black uppercase tracking-wider">Alasan Pembatalan</p>
                            <p class="font-medium mt-1" id="modal-cancel-reason">-</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Payment & Order stats -->
                <div class="space-y-4 bg-[var(--bg-main)]/50 p-6 rounded-2xl border border-[var(--border)]">
                    <h4 class="text-xs font-black uppercase tracking-widest text-emerald-500">Pembayaran & Status</h4>
                    <div class="space-y-3 text-xs sm:text-sm">
                        <div>
                            <p class="text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider">Metode Pembayaran</p>
                            <p class="font-bold text-[var(--text-main)]" id="modal-payment-method">-</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider">Status Pembayaran</p>
                            <p class="font-bold" id="modal-payment-status-text">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="space-y-3">
                <h4 class="text-xs font-black uppercase tracking-widest text-purple-500">Menu Yang Dipesan</h4>
                <div class="border border-[var(--border)] rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs sm:text-sm">
                            <thead>
                                <tr class="bg-[var(--bg-main)] border-b border-[var(--border)] text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider">
                                    <th class="px-6 py-4">Menu</th>
                                    <th class="px-6 py-4 text-center">Harga Satuan</th>
                                    <th class="px-6 py-4 text-center">Jumlah</th>
                                    <th class="px-6 py-4 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="modal-items-tbody" class="divide-y divide-[var(--border)] bg-[var(--bg-card)]">
                                <!-- Items inserted dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Price Breakdown Summary -->
            <div class="bg-[var(--bg-main)]/30 border border-[var(--border)] p-6 rounded-2xl ml-auto max-w-sm space-y-2 text-xs sm:text-sm">
                <div class="flex justify-between">
                    <span class="text-[var(--text-muted)] font-semibold">Subtotal</span>
                    <span class="text-[var(--text-main)] font-bold" id="modal-summary-subtotal">Rp 0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[var(--text-muted)] font-semibold">Ongkos Kirim</span>
                    <span class="text-[var(--text-main)] font-bold" id="modal-summary-shipping">Rp 0</span>
                </div>
                <div class="flex justify-between text-rose-500">
                    <span class="font-semibold">Potongan Diskon</span>
                    <span class="font-bold" id="modal-summary-discount">-Rp 0</span>
                </div>
                <div class="flex justify-between text-purple-500">
                    <span class="font-semibold">Diskon Member</span>
                    <span class="font-bold" id="modal-summary-member">-Rp 0</span>
                </div>
                <div class="h-px bg-[var(--border)] my-2"></div>
                <div class="flex justify-between text-sm sm:text-base font-black">
                    <span class="text-[var(--text-main)]">Total Akhir</span>
                    <span class="text-emerald-500" id="modal-summary-total">Rp 0</span>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-[var(--border)]">
                <button onclick="closeDetailsModal()" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-[var(--text-main)] font-bold rounded-2xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- APP ANDROID SYNC SIMULATOR MODAL -->
<div id="sync-simulator-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm hidden animate-fade-in">
    <div class="bg-[var(--bg-card)] border border-[var(--border)] w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-[2rem] sm:rounded-[3rem] p-6 sm:p-10 shadow-2xl relative">
        <button onclick="closeSyncModal()" class="absolute top-6 right-6 w-10 h-10 bg-[var(--bg-main)] border border-[var(--border)] rounded-full flex items-center justify-center text-[var(--text-main)] hover:text-rose-500 transition-colors">
            ✕
        </button>

        <div class="text-left space-y-2 mb-6">
            <h3 class="text-2xl font-black text-[var(--text-main)] tracking-tight">Simulasikan Sinkronisasi Android</h3>
            <p class="text-xs text-[var(--text-muted)] font-medium">Buat pesanan simulasi seolah-olah dikirim dari aplikasi Android untuk menguji sistem real-time.</p>
        </div>

        <form id="simulator-form" onsubmit="event.preventDefault(); submitSimulatorSync();" class="space-y-6 text-left">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Section -->
                <div class="space-y-4 bg-[var(--bg-main)]/50 p-5 rounded-2xl border border-[var(--border)]">
                    <h4 class="text-xs font-black uppercase tracking-widest text-blue-500">Data Pelanggan</h4>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Pilih User Terdaftar</label>
                            <select id="sim-customer-select" onchange="onSimulatorCustomerChange()" class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none">
                                <option value="new">+ Pelanggan Baru (Input Manual)</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Nama Lengkap</label>
                            <input type="text" id="sim-customer-name" required class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none focus:border-blue-500/50">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Email</label>
                                <input type="email" id="sim-customer-email" required class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none focus:border-blue-500/50">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">No HP</label>
                                <input type="text" id="sim-customer-phone" required class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none focus:border-blue-500/50">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Alamat Lengkap</label>
                            <textarea id="sim-address" required rows="2" class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none focus:border-blue-500/50"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Parameters Section -->
                <div class="space-y-4 bg-[var(--bg-main)]/50 p-5 rounded-2xl border border-[var(--border)]">
                    <h4 class="text-xs font-black uppercase tracking-widest text-emerald-500">Parameter Pesanan</h4>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Tanggal Pengiriman</label>
                            <input type="datetime-local" id="sim-delivery-date" class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none focus:border-emerald-500/50">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Ongkir (Rp)</label>
                                <input type="number" id="sim-shipping" value="15000" oninput="renderSimulatorCart()" class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none focus:border-emerald-500/50">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Diskon Voucher (Rp)</label>
                                <input type="number" id="sim-discount" value="0" oninput="renderSimulatorCart()" class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none focus:border-emerald-500/50">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Metode Bayar</label>
                                <select id="sim-payment-method" class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none">
                                    <option value="COD">COD</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="Saldo Aish">Saldo Aish</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Status Awal</label>
                                <select id="sim-status" class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none">
                                    <option value="Pending">⏰ Pending</option>
                                    <option value="Diproses" selected>⏳ Diproses</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="space-y-4 bg-[var(--bg-main)]/30 p-5 rounded-2xl border border-[var(--border)]">
                <h4 class="text-xs font-black uppercase tracking-widest text-purple-500">Pilih Menu & Kuantitas</h4>
                
                <div class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Menu Katalog</label>
                        <select id="sim-menu-select" class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none">
                            <option value="">-- Pilih Menu --</option>
                            @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->emoji }} {{ $menu->name }} (Rp {{ number_format($menu->price) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-24">
                        <label class="block text-[9px] font-black text-[var(--text-muted)] uppercase tracking-wider mb-1">Kuantitas</label>
                        <input type="number" id="sim-menu-qty" value="1" min="1" class="w-full text-xs font-semibold p-3 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] text-[var(--text-main)] outline-none">
                    </div>
                    <button type="button" onclick="addSimulatorItem()" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl transition duration-300">
                        + Tambah Menu
                    </button>
                </div>

                <div class="border border-[var(--border)] rounded-xl overflow-hidden mt-3">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[var(--bg-main)] border-b border-[var(--border)] font-black text-[var(--text-muted)] uppercase tracking-wider">
                                <th class="px-4 py-3">Menu</th>
                                <th class="px-4 py-3 text-center">Harga</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sim-cart-tbody" class="divide-y divide-[var(--border)] bg-[var(--bg-card)]">
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-[var(--text-muted)] font-medium">Belum ada menu yang ditambahkan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-sm pt-2">
                    <div>
                        <span class="text-[var(--text-muted)] font-semibold text-xs">Subtotal:</span>
                        <span id="sim-subtotal-val" class="font-bold text-[var(--text-main)] text-xs">Rp 0</span>
                    </div>
                    <div>
                        <span class="text-[var(--text-muted)] font-semibold text-xs">Total Akhir:</span>
                        <span id="sim-total-val" class="font-black text-emerald-500 text-sm sm:text-base">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-[var(--border)]">
                <button type="button" onclick="closeSyncModal()" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-[var(--text-main)] font-bold rounded-2xl transition">
                    Batal
                </button>
                <button type="submit" class="px-8 py-3 bg-blue-500 hover:bg-blue-600 text-white font-black rounded-2xl transition shadow-lg shadow-blue-500/20 active:scale-95">
                    SIMULASIKAN SYNC
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Pass PHP arrays to JS
    window.appMenus = {!! json_encode($menus) !!};
    window.appUsers = {!! json_encode($users) !!};

    function handleStatusChange(select, orderId, currentStatus) {
        const newStatus = select.value;
        if (newStatus === 'Dibatalkan' || newStatus === 'CANCELLED') {
            const reason = prompt("Silakan masukkan alasan pembatalan:");
            if (reason === null) {
                // User cancelled the prompt, restore current status
                select.value = currentStatus;
                if (currentViewedOrder) {
                    document.getElementById('modal-order-status-select').value = currentStatus;
                }
                return;
            }
            submitStatusUpdate(orderId, newStatus, reason);
        } else {
            if (confirm(`Ubah status pesanan menjadi ${newStatus}?`)) {
                submitStatusUpdate(orderId, newStatus, null);
            } else {
                select.value = currentStatus;
                if (currentViewedOrder) {
                    document.getElementById('modal-order-status-select').value = currentStatus;
                }
            }
        }
    }

    async function submitStatusUpdate(orderId, status, cancelReason) {
        try {
            const response = await fetch(`/admin/orders/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PUT',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: status,
                    cancel_reason: cancelReason
                })
            });
            
            const data = await response.json();
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert("Gagal memperbarui status: " + data.message);
                window.location.reload();
            }
        } catch (error) {
            console.error("Error updating status:", error);
            alert("Terjadi kesalahan saat memperbarui status.");
            window.location.reload();
        }
    }

    // Modal Details Logic
    let currentViewedOrder = null;

    function viewOrderDetails(order) {
        currentViewedOrder = order;
        
        document.getElementById('modal-order-id').innerText = order.order_id ? `#${order.order_id}` : `#ORD-${order.id}`;
        document.getElementById('modal-order-emoji').innerText = order.emoji || '🍱';
        document.getElementById('modal-order-date').innerText = `Tanggal Pesanan: ${order.created_at ? new Date(order.created_at).toLocaleString('id-ID', {day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'}) : '-'}`;
        
        const statusSelect = document.getElementById('modal-order-status-select');
        let displayStatus = order.status;
        if (['Ditolak', 'CANCELLED'].includes(displayStatus)) displayStatus = 'Dibatalkan';
        else if (['Diterima', 'PROCESSING'].includes(displayStatus)) displayStatus = 'Diproses';
        else if (['PENDING'].includes(displayStatus)) displayStatus = 'Pending';
        else if (['SHIPPED'].includes(displayStatus)) displayStatus = 'Dikirim';
        else if (['COMPLETED'].includes(displayStatus)) displayStatus = 'Selesai';
        statusSelect.value = displayStatus;
        updateSelectColor(statusSelect);
        
        const payBadge = document.getElementById('modal-order-payment-status');
        payBadge.innerText = order.payment_status || 'COD';
        if (order.payment_status === 'PAID') {
            payBadge.className = 'text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border border-emerald-500/20 bg-emerald-500/10 text-emerald-500';
        } else if (order.payment_status === 'UNPAID') {
            payBadge.className = 'text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border border-rose-500/20 bg-rose-500/10 text-rose-500';
        } else {
            payBadge.className = 'text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border border-amber-500/20 bg-amber-500/10 text-amber-500';
        }
        
        document.getElementById('modal-customer-name').innerText = order.customer_name || 'Pelanggan';
        document.getElementById('modal-customer-contact').innerText = `${order.user_email} ${order.customer_phone ? '· ' + order.customer_phone : ''}`;
        document.getElementById('modal-shipping-address').innerText = order.shipping_address || 'Tidak ada alamat';
        
        const delDate = order.delivery_date ? new Date(order.delivery_date).toLocaleString('id-ID', {day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'}) : 'Segera / Sesuai Jadwal';
        document.getElementById('modal-delivery-date').innerText = delDate;
        
        const cancelReasonContainer = document.getElementById('modal-cancel-reason-container');
        if (order.status === 'Dibatalkan' || order.status === 'CANCELLED') {
            document.getElementById('modal-cancel-reason').innerText = order.cancel_reason || 'Tidak ditentukan';
            cancelReasonContainer.classList.remove('hidden');
        } else {
            cancelReasonContainer.classList.add('hidden');
        }
        
        document.getElementById('modal-payment-method').innerText = order.payment_method || 'COD';
        document.getElementById('modal-payment-status-text').innerText = order.payment_status || 'COD';
        document.getElementById('modal-payment-status-text').className = `font-bold ${order.payment_status === 'PAID' ? 'text-emerald-500' : 'text-amber-500'}`;
        
        const tbody = document.getElementById('modal-items-tbody');
        tbody.innerHTML = '';
        
        let parsedItems = [];
        try {
            parsedItems = typeof order.items_json === 'string' ? JSON.parse(order.items_json) : order.items_json;
            if (!Array.isArray(parsedItems)) {
                parsedItems = [];
            }
        } catch (e) {
            console.error("Failed to parse items_json:", e);
        }
        
        if (parsedItems.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-6 text-center text-xs text-[var(--text-muted)] font-medium">
                        Detail item tidak tersedia (Format JSON tidak valid).
                    </td>
                </tr>
            `;
        } else {
            parsedItems.forEach(item => {
                const qty = item.quantity || item.qty || 1;
                const price = item.price || item.harga || 0;
                const itemTotal = price * qty;
                
                tbody.innerHTML += `
                    <tr class="hover:bg-[var(--bg-main)]/20 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">${item.emoji || '🍱'}</span>
                                <div>
                                    <p class="font-bold text-[var(--text-main)] text-xs sm:text-sm">${item.name || 'Menu'}</p>
                                    <p class="text-[9px] text-[var(--text-muted)] uppercase tracking-wider font-semibold">${item.category || '-'}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-[var(--text-main)]">Rp ${price.toLocaleString('id-ID')}</td>
                        <td class="px-6 py-4 text-center font-bold text-[var(--text-main)]">${qty}</td>
                        <td class="px-6 py-4 text-right font-black text-[var(--text-main)]">Rp ${itemTotal.toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
        }
        
        const subtotal = order.subtotal || order.total_price;
        const shipping = order.shipping_cost || 0;
        const discount = order.discount || 0;
        const memberDiscount = order.member_discount || 0;
        const total = order.total_price;
        
        document.getElementById('modal-summary-subtotal').innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;
        document.getElementById('modal-summary-shipping').innerText = `Rp ${shipping.toLocaleString('id-ID')}`;
        document.getElementById('modal-summary-discount').innerText = `-Rp ${discount.toLocaleString('id-ID')}`;
        document.getElementById('modal-summary-member').innerText = `-Rp ${memberDiscount.toLocaleString('id-ID')}`;
        document.getElementById('modal-summary-total').innerText = `Rp ${total.toLocaleString('id-ID')}`;
        
        document.getElementById('order-details-modal').classList.remove('hidden');
    }

    function updateSelectColor(select) {
        const val = select.value;
        select.className = "text-xs font-black px-4 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg-main)] outline-none cursor-pointer " + 
            (['Selesai','COMPLETED'].includes(val) ? 'text-emerald-500' :
            (val === 'Dikirim' ? 'text-orange-500' :
            (['Diproses','PROCESSING','Diterima'].includes(val) ? 'text-blue-500' : 
            (['Dibatalkan','CANCELLED','Ditolak'].includes(val) ? 'text-rose-500' : 'text-yellow-500'))));
    }

    function closeDetailsModal() {
        document.getElementById('order-details-modal').classList.add('hidden');
    }

    function modalStatusChanged(select) {
        if (!currentViewedOrder) return;
        handleStatusChange(select, currentViewedOrder.id, currentViewedOrder.status);
    }

    // Simulator Modals Logic
    let simulatorCart = [];

    function openSyncModal() {
        document.getElementById('sync-simulator-modal').classList.remove('hidden');
        simulatorCart = [];
        renderSimulatorCart();
        document.getElementById('simulator-form').reset();
    }

    function closeSyncModal() {
        document.getElementById('sync-simulator-modal').classList.add('hidden');
    }

    function onSimulatorCustomerChange() {
        const select = document.getElementById('sim-customer-select');
        const userId = select.value;
        
        if (userId === 'new') {
            document.getElementById('sim-customer-name').value = '';
            document.getElementById('sim-customer-email').value = '';
            document.getElementById('sim-customer-phone').value = '';
            document.getElementById('sim-address').value = '';
            return;
        }
        
        const user = window.appUsers.find(u => u.id == userId);
        if (user) {
            document.getElementById('sim-customer-name').value = user.name || '';
            document.getElementById('sim-customer-email').value = user.email || '';
            document.getElementById('sim-customer-phone').value = user.phone || '';
            document.getElementById('sim-address').value = user.address || '';
        }
    }

    function addSimulatorItem() {
        const menuSelect = document.getElementById('sim-menu-select');
        const qtyInput = document.getElementById('sim-menu-qty');
        const menuId = menuSelect.value;
        const qty = parseInt(qtyInput.value) || 1;
        
        if (!menuId) return alert("Pilih menu terlebih dahulu!");
        
        const menu = window.appMenus.find(m => m.id == menuId);
        if (!menu) return;
        
        const existingIndex = simulatorCart.findIndex(item => item.id == menuId);
        if (existingIndex > -1) {
            simulatorCart[existingIndex].quantity += qty;
        } else {
            simulatorCart.push({
                id: menu.id,
                name: menu.name,
                price: parseInt(menu.price),
                quantity: qty,
                category: menu.category,
                emoji: menu.emoji || '🍱',
                image_url: menu.image_url
            });
        }
        
        renderSimulatorCart();
        menuSelect.value = '';
        qtyInput.value = '1';
    }

    function removeSimulatorItem(index) {
        simulatorCart.splice(index, 1);
        renderSimulatorCart();
    }

    function renderSimulatorCart() {
        const tbody = document.getElementById('sim-cart-tbody');
        if (simulatorCart.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="px-4 py-6 text-center text-[var(--text-muted)] font-medium">Belum ada menu yang ditambahkan.</td></tr>`;
            document.getElementById('sim-subtotal-val').innerText = 'Rp 0';
            document.getElementById('sim-total-val').innerText = 'Rp 0';
            return;
        }

        tbody.innerHTML = '';
        let subtotal = 0;
        simulatorCart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            tbody.innerHTML += `
                <tr class="border-b border-[var(--border)] text-xs hover:bg-[var(--bg-main)]/35 transition-all duration-200">
                    <td class="px-4 py-3 font-semibold text-[var(--text-main)] flex items-center gap-2">
                        <span>${item.emoji}</span>
                        <span>${item.name}</span>
                    </td>
                    <td class="px-4 py-3 text-center">Rp ${item.price.toLocaleString('id-ID')}</td>
                    <td class="px-4 py-3 text-center font-bold text-[var(--text-main)]">${item.quantity}</td>
                    <td class="px-4 py-3 text-right font-black text-[var(--text-main)]">Rp ${itemTotal.toLocaleString('id-ID')}</td>
                    <td class="px-4 py-3 text-center">
                        <button type="button" onclick="removeSimulatorItem(${index})" class="text-rose-500 font-bold hover:underline transition-colors hover:text-rose-700">✕</button>
                    </td>
                </tr>
            `;
        });
        
        document.getElementById('sim-subtotal-val').innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;
        
        const shipping = parseInt(document.getElementById('sim-shipping').value) || 0;
        const discount = parseInt(document.getElementById('sim-discount').value) || 0;
        const total = subtotal + shipping - discount;
        document.getElementById('sim-total-val').innerText = `Rp ${total.toLocaleString('id-ID')}`;
    }

    async function submitSimulatorSync() {
        const customerName = document.getElementById('sim-customer-name').value.trim();
        const customerEmail = document.getElementById('sim-customer-email').value.trim();
        const customerPhone = document.getElementById('sim-customer-phone').value.trim();
        const address = document.getElementById('sim-address').value.trim();
        const deliveryDate = document.getElementById('sim-delivery-date').value;
        const paymentMethod = document.getElementById('sim-payment-method').value;
        const status = document.getElementById('sim-status').value;
        const shipping = parseInt(document.getElementById('sim-shipping').value) || 0;
        const discount = parseInt(document.getElementById('sim-discount').value) || 0;
        
        if (!customerEmail || !customerName || simulatorCart.length === 0) {
            return alert("Nama, Email Pelanggan, dan Menu Pesanan wajib diisi!");
        }
        
        let subtotal = 0;
        simulatorCart.forEach(item => subtotal += item.price * item.quantity);
        const totalPrice = subtotal + shipping - discount;
        
        const randomId = "ORD-" + Math.floor(100000 + Math.random() * 900000);
        const itemsTitle = simulatorCart.map(item => `${item.name} x${item.quantity}`).join(', ');
        const itemsJson = JSON.stringify(simulatorCart);
        
        const requestData = {
            order_id: randomId,
            customer_name: customerName,
            customer_email: customerEmail,
            customer_phone: customerPhone,
            items_title: itemsTitle,
            items_json: itemsJson,
            total_price: totalPrice,
            subtotal: subtotal,
            shipping_cost: shipping,
            discount: discount,
            address: address,
            status: status,
            delivery_date: deliveryDate,
            payment_method: paymentMethod,
            emoji: simulatorCart[0]?.emoji || '🍱'
        };
        
        try {
            const response = await fetch('/api/place_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestData)
            });
            
            const data = await response.json();
            if (data.status === 'success') {
                alert("Berhasil mensimulasikan sinkronisasi pesanan dari Android: " + data.message);
                window.location.reload();
            } else {
                alert("Gagal mensimulasikan sinkronisasi: " + data.message);
            }
        } catch (e) {
            console.error("Simulator Error:", e);
            alert("Terjadi kesalahan saat mengirim data simulasi.");
        }
    }

    // --- Real-time Orders Sync ---
    const initialOrders = {
        @foreach($orders as $order)
            "{{ $order->id }}": "{{ $order->status }}",
        @endforeach
    };
    let lastOrderCount = {{ $orders->total() }};
    const orderAudio = new Audio('/mixkit-bell-notification-933.wav');

    async function checkNewOrders() {
        try {
            const response = await fetch('{{ route("admin.orders.latest_raw") }}');
            if (response.ok) {
                const data = await response.json();
                const orders = data.orders;
                const stats = data.stats;
                
                let needsReload = false;

                // 1. Check if total order count has changed (new or deleted order)
                if (stats && stats.total_orders !== lastOrderCount) {
                    needsReload = true;
                }

                // 2. Check if the status of any currently visible order has changed
                if (orders && orders.length > 0) {
                    orders.forEach(order => {
                        const currentStatus = initialOrders[order.id];
                        if (currentStatus !== undefined && currentStatus !== order.status) {
                            needsReload = true;
                        }
                    });
                }

                if (needsReload) {
                    orderAudio.play().catch(e => console.log("Audio play blocked"));
                    
                    if (Notification.permission === "granted") {
                        new Notification("Update Pesanan!", {
                            body: `Daftar pesanan telah diperbarui secara otomatis.`,
                            icon: "/favicon.ico"
                        });
                    }
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            }
        } catch (error) {
            console.error("Failed to check new orders:", error);
        }
    }

    if (Notification.permission !== "denied" && Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    // Subscribe to Laravel Echo orders channel for real-time updates
    window.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.channel('orders')
                .listen('.OrderStatusUpdated', (e) => {
                    console.log("Real-time order sync triggered:", e);
                    // Play sound
                    orderAudio.play().catch(err => console.log("Audio play blocked"));
                    
                    // Show notification
                    if (Notification.permission === "granted") {
                        new Notification("Update Pesanan!", {
                            body: `Pesanan #${e.order.order_id || e.order.id} diupdate menjadi: ${e.order.status}`,
                            icon: "/favicon.ico"
                        });
                    }

                    // Reload page instantly
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                });
        }
    });

    setInterval(checkNewOrders, 5000);
</script>
@endpush

