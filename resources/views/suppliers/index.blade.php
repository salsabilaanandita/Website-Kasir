@extends('layouts.app')
@section('title', 'Supplier')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Daftar Supplier</h1>
            <p class="text-xs text-[#86868b] mt-1">Kelola data pemasok barang dan kontak operasional</p>
        </div>
        <a href="{{ route('suppliers.create') }}" class="btn-apple-primary">
            <i class="fas fa-plus text-xs"></i>
            <span>Tambah Supplier</span>
        </a>
    </div>

    <div class="apple-card">
        <div class="px-5 py-3.5 border-b border-[#e5e5ea] flex items-center justify-between bg-white">
            <div class="flex items-center gap-2">
                <i class="fas fa-truck text-xs text-[#0071e3]"></i>
                <span class="text-xs font-semibold text-[#1d1d1f]">Data Supplier</span>
            </div>
            <span class="apple-badge">
                Total: {{ $suppliers->total() }} supplier
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Supplier</th>
                        <th>Kontak Person</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th style="text-align: center; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $s)
                    <tr>
                        <td class="text-[#86868b] font-medium">{{ $suppliers->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="font-semibold text-[#1d1d1f]">{{ $s->nama_supplier }}</div>
                            @if($s->alamat)
                                <div class="text-[11px] text-[#86868b]">{{ Str::limit($s->alamat, 40) }}</div>
                            @endif
                        </td>
                        <td class="text-[#1d1d1f]">{{ $s->kontak_person ?? '-' }}</td>
                        <td class="text-[#1d1d1f]">{{ $s->telepon ?? '-' }}</td>
                        <td class="text-[#0071e3]">{{ $s->email ?? '-' }}</td>
                        <td style="text-align: center;">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('suppliers.edit', $s->id) }}" class="btn-apple-ghost" title="Edit">
                                    <i class="fas fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus supplier ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-apple-danger" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-[#86868b]">
                            <div class="w-12 h-12 bg-[#f5f5f7] rounded-full flex items-center justify-center mx-auto mb-3 text-[#86868b]">
                                <i class="fas fa-truck text-xl"></i>
                            </div>
                            <p class="text-xs font-medium">Belum ada data supplier</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
            <div class="p-4 border-t border-[#e5e5ea]">{{ $suppliers->links() }}</div>
        @endif
    </div>
</div>
@endsection
