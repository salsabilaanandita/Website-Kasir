@extends('layouts.app')
@section('title', 'Stock Opname')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('inventory.index') }}" class="btn-apple-ghost inline-flex items-center gap-1.5 text-xs">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Inventori
        </a>
        <h1 class="text-xl font-bold tracking-tight text-[#1d1d1f]">Stock Opname</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Form Column (4 Cols) --}}
        <div class="lg:col-span-4">
            <div class="apple-card overflow-hidden">
                <div class="p-4 border-b border-[#f0f0f0] bg-[#fafafc]">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-[#1d1d1f] flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-[#0071e3]"></i> Catat Sesi Opname
                    </h2>
                </div>
                <form action="{{ route('inventory.opname.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Tanggal Pelaksanaan <span class="text-[#e03131]">*</span></label>
                        <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="apple-input text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="3" placeholder="Misal: Opname rutin akhir bulan..." class="apple-input text-xs"></textarea>
                    </div>
                    <button type="submit" class="btn-apple-primary w-full py-2.5 text-xs justify-center flex items-center gap-2">
                        Simpan Catatan Opname
                    </button>
                </form>
            </div>
        </div>

        {{-- History Column (8 Cols) --}}
        <div class="lg:col-span-8">
            <div class="apple-card overflow-hidden">
                <div class="p-4 border-b border-[#f0f0f0] bg-[#fafafc] flex items-center justify-between">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-[#1d1d1f]">Riwayat Stock Opname</h2>
                    <span class="text-xs text-[#86868b]">{{ $opnames->total() ?? 0 }} sesi</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="apple-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>No. Opname</th>
                                <th>Tanggal</th>
                                <th class="text-center">Status</th>
                                <th>Catatan</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($opnames as $o)
                                <tr>
                                    <td class="text-[#86868b] font-medium">{{ $opnames->firstItem() + $loop->index }}</td>
                                    <td class="font-mono text-xs font-semibold text-[#0071e3]">{{ $o->no_opname }}</td>
                                    <td class="text-xs text-[#1d1d1f] font-medium">{{ $o->tanggal->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <span class="apple-badge bg-[#eafaf1] text-[#1e7e34] border-[#c3f0d4]">
                                            Selesai
                                        </span>
                                    </td>
                                    <td class="text-xs text-[#86868b] max-w-xs">{{ $o->catatan ?? '-' }}</td>
                                    <td class="text-xs text-[#1d1d1f]">{{ $o->user?->name ?? 'Sistem' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12 text-[#86868b]">
                                        <i class="fas fa-clipboard-list text-3xl mb-3 block opacity-40"></i>
                                        <span class="text-sm">Belum ada riwayat stock opname</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($opnames->hasPages())
                    <div class="p-4 border-t border-[#f0f0f0]">
                        {{ $opnames->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
