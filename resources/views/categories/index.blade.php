@extends('layouts.app')
@section('title', 'Kategori')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Kategori Produk</h1>
            <p class="text-xs text-[#86868b] mt-1">Kelola pengelompokan produk untuk mempermudah pencarian dan filter di kasir</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn-apple-primary">
            <i class="fas fa-plus text-xs"></i>
            <span>Tambah Kategori</span>
        </a>
    </div>

    <div class="apple-card">
        <div class="px-5 py-3.5 border-b border-[#e5e5ea] flex items-center justify-between bg-white">
            <div class="flex items-center gap-2">
                <i class="fas fa-tags text-xs text-[#0071e3]"></i>
                <span class="text-xs font-semibold text-[#1d1d1f]">Daftar Kategori</span>
            </div>
            <span class="apple-badge">
                Total: {{ $categories->total() }} kategori
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th style="text-align: center; width: 140px;">Jumlah Produk</th>
                        <th style="text-align: center; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td class="text-[#86868b] font-medium">{{ $categories->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="font-semibold text-[#1d1d1f]">{{ $cat->nama_kategori }}</div>
                        </td>
                        <td class="text-[#86868b]">{{ $cat->deskripsi ?? '-' }}</td>
                        <td style="text-align: center;">
                            <span class="apple-badge apple-badge-blue font-semibold">
                                {{ $cat->products_count ?? 0 }} produk
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('categories.edit', $cat->id) }}" class="btn-apple-ghost" title="Edit">
                                    <i class="fas fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
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
                        <td colspan="5" class="text-center py-12 text-[#86868b]">
                            <div class="w-12 h-12 bg-[#f5f5f7] rounded-full flex items-center justify-center mx-auto mb-3 text-[#86868b]">
                                <i class="fas fa-tags text-xl"></i>
                            </div>
                            <p class="text-xs font-medium">Belum ada kategori terdaftar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
            <div class="p-4 border-t border-[#e5e5ea]">{{ $categories->links() }}</div>
        @endif
    </div>
</div>
@endsection
