@extends('layouts.app')
@section('title', 'Shift Kasir')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Manajemen Shift</h1>
            <p class="text-xs text-[#86868b] mt-1">Kelola sesi buka/tutup kasir dan rekonsiliasi kas harian</p>
        </div>
        <div>
            @if(!$activeShift)
                <a href="{{ route('shifts.open') }}" class="btn-apple-primary">
                    <i class="fas fa-play text-xs"></i>
                    <span>Buka Shift Baru</span>
                </a>
            @else
                <a href="{{ route('shifts.close') }}" class="btn-apple-danger font-semibold">
                    <i class="fas fa-stop text-xs"></i>
                    <span>Tutup Shift Aktif</span>
                </a>
            @endif
        </div>
    </div>

    @if($activeShift)
    <div class="apple-card p-5 bg-[#f0f6ff] border-[#cce0ff]">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 bg-[#0071e3] text-white rounded-xl flex items-center justify-center text-sm shadow-sm">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="text-xs font-semibold text-[#0071e3] uppercase tracking-wider">Shift Aktif</div>
                    <div class="text-xs text-[#1d1d1f] mt-0.5">
                        Dimulai: {{ \Carbon\Carbon::parse($activeShift->waktu_buka)->format('d M Y, H:i') }} WIB
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 w-full md:w-auto">
                <div class="bg-white p-2.5 px-4 rounded-xl border border-[#d2d2d7] text-center">
                    <div class="text-[10px] font-semibold text-[#86868b] uppercase">Modal Awal</div>
                    <div class="text-xs font-bold text-[#1d1d1f]">Rp {{ number_format($activeShift->modal_awal, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white p-2.5 px-4 rounded-xl border border-[#d2d2d7] text-center">
                    <div class="text-[10px] font-semibold text-[#86868b] uppercase">Total Tunai</div>
                    <div class="text-xs font-bold text-[#1d1d1f]">Rp {{ number_format($activeShift->total_tunai, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white p-2.5 px-4 rounded-xl border border-[#d2d2d7] text-center">
                    <div class="text-[10px] font-semibold text-[#86868b] uppercase">Kas Harapan</div>
                    <div class="text-xs font-bold text-[#16a34a]">Rp {{ number_format($activeShift->modal_awal + $activeShift->total_tunai, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="apple-card">
        <div class="px-5 py-3.5 border-b border-[#e5e5ea] flex items-center justify-between bg-white">
            <div class="flex items-center gap-2">
                <i class="fas fa-clock-rotate-left text-xs text-[#0071e3]"></i>
                <span class="text-xs font-semibold text-[#1d1d1f]">Riwayat Shift Selesai</span>
            </div>
            <span class="apple-badge">
                Total: {{ $shifts->total() }} shift
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Petugas Kasir</th>
                        <th>Waktu Buka / Tutup</th>
                        <th style="text-align: right;">Modal Awal</th>
                        <th style="text-align: right;">Penjualan Total</th>
                        <th style="text-align: right;">Kas Akhir Aktual</th>
                        <th style="text-align: right;">Selisih Kas</th>
                        <th style="text-align: center; width: 100px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                    <tr>
                        <td class="text-[#86868b] font-medium">{{ $shifts->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="font-semibold text-[#1d1d1f] text-xs">{{ $shift->user->name ?? 'Kasir' }}</div>
                        </td>
                        <td>
                            <div class="font-semibold text-[#1d1d1f] text-xs">
                                {{ \Carbon\Carbon::parse($shift->waktu_buka)->format('d M Y H:i') }}
                            </div>
                            <div class="text-[11px] text-[#86868b]">
                                {{ $shift->waktu_tutup ? \Carbon\Carbon::parse($shift->waktu_tutup)->format('d M Y H:i') : 'Masih Berjalan' }}
                            </div>
                        </td>
                        <td style="text-align: right;" class="text-[#1d1d1f]">
                            Rp {{ number_format($shift->modal_awal, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right;" class="text-[#1d1d1f] font-semibold">
                            Rp {{ number_format($shift->total_penjualan ?? 0, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right;" class="text-[#1d1d1f] font-semibold">
                            {{ $shift->kas_akhir !== null ? 'Rp ' . number_format($shift->kas_akhir, 0, ',', '.') : '-' }}
                        </td>
                        <td style="text-align: right;">
                            @php 
                                $harapan = $shift->modal_awal + $shift->total_tunai;
                                $selisih = $shift->kas_akhir !== null ? ($shift->kas_akhir - $harapan) : null; 
                            @endphp
                            @if($selisih === null)
                                <span class="text-[#86868b] text-xs">-</span>
                            @elseif($selisih == 0)
                                <span class="text-[#16a34a] font-semibold text-xs">Rp 0 (Klop)</span>
                            @elseif($selisih < 0)
                                <span class="text-[#d70015] font-semibold text-xs">- Rp {{ number_format(abs($selisih), 0, ',', '.') }}</span>
                            @else
                                <span class="text-[#0071e3] font-semibold text-xs">+ Rp {{ number_format($selisih, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if(in_array($shift->status, ['buka', 'open']))
                                <span class="apple-badge apple-badge-green">Aktif</span>
                            @else
                                <span class="apple-badge">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-[#86868b]">
                            <div class="w-12 h-12 bg-[#f5f5f7] rounded-full flex items-center justify-center mx-auto mb-3 text-[#86868b]">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <p class="text-xs font-medium">Belum ada riwayat shift tersimpan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shifts->hasPages())
            <div class="p-4 border-t border-[#e5e5ea]">
                {{ $shifts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
