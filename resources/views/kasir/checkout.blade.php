@extends('layouts.app')
@section('title', 'Checkout POS')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('kasir.index') }}" class="btn-apple-ghost inline-flex items-center gap-1.5 text-xs">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Kasir
        </a>
        <h1 class="text-xl font-bold tracking-tight text-[#1d1d1f]">Checkout Transaksi</h1>
    </div>

    <form action="{{ route('kasir.proses') }}" method="POST" id="checkoutForm" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf
        
        {{-- Left Column: Order Items & Member lookup (7 Cols) --}}
        <div class="lg:col-span-7 space-y-5">
            {{-- Cart Items Card --}}
            <div class="apple-card p-5">
                <div class="flex items-center justify-between pb-3 border-b border-[#f0f0f0] mb-4">
                    <h2 class="text-sm font-semibold text-[#1d1d1f] tracking-tight flex items-center gap-2">
                        <i class="fas fa-bag-shopping text-[#0071e3] text-xs"></i> Rincian Pesanan
                    </h2>
                    <span class="text-xs text-[#86868b]">{{ count($cartItems) }} jenis barang</span>
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    @foreach($cartItems as $item)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-[#fafafc] border border-[#f0f0f0]">
                            <div class="w-12 h-12 rounded-lg bg-white border border-[#e5e5ea] flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if($item['product']->img)
                                    <img src="{{ asset('storage/' . $item['product']->img) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-box text-[#86868b] text-xs"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-semibold text-[#1d1d1f] truncate">{{ $item['product']->nama_produk }}</div>
                                <div class="text-[11px] text-[#86868b] tabular-nums mt-0.5">
                                    Rp {{ number_format($item['product']->harga, 0, ',', '.') }} &times; {{ $item['qty'] }}
                                </div>
                            </div>
                            <div class="text-xs font-bold text-[#1d1d1f] tabular-nums">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Member Card --}}
            <div class="apple-card p-5">
                <h2 class="text-sm font-semibold text-[#1d1d1f] tracking-tight flex items-center gap-2 mb-3">
                    <i class="fas fa-id-card text-[#0071e3] text-xs"></i> Data Pelanggan Member (Opsional)
                </h2>
                
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <input type="text" id="memberPhone" class="apple-input text-xs" placeholder="Ketik No. HP Member...">
                    </div>
                    <button type="button" class="btn-apple-secondary text-xs px-4" onclick="cekMember()" id="btnCekMember">
                        Cek
                    </button>
                </div>

                <div id="memberResult" class="mt-3 hidden p-3 rounded-xl bg-[#e8f2ff] border border-[#cce4ff] text-xs"></div>
                <input type="hidden" name="member_id" id="member_id">
            </div>
        </div>

        {{-- Right Column: Payment & Final Summary (5 Cols) --}}
        <div class="lg:col-span-5 space-y-5">
            <div class="apple-card p-5 sticky top-20">
                <h2 class="text-sm font-semibold text-[#1d1d1f] tracking-tight mb-4 flex items-center gap-2">
                    <i class="fas fa-credit-card text-[#0071e3] text-xs"></i> Pembayaran
                </h2>

                {{-- Payment Methods --}}
                <div class="space-y-1.5 mb-4">
                    <label class="block text-[11px] font-semibold text-[#86868b] uppercase tracking-wider">Metode Pembayaran</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="metode_bayar" value="Tunai" checked class="peer sr-only">
                            <div class="p-2.5 rounded-xl border border-[#d2d2d7] text-center peer-checked:border-[#0071e3] peer-checked:bg-[#e8f2ff] transition-all">
                                <i class="fas fa-money-bill-wave text-xs block mb-1 text-[#86868b] peer-checked:text-[#0071e3]"></i>
                                <span class="text-xs font-semibold text-[#1d1d1f]">Tunai</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="metode_bayar" value="QRIS" class="peer sr-only">
                            <div class="p-2.5 rounded-xl border border-[#d2d2d7] text-center peer-checked:border-[#0071e3] peer-checked:bg-[#e8f2ff] transition-all">
                                <i class="fas fa-qrcode text-xs block mb-1 text-[#86868b] peer-checked:text-[#0071e3]"></i>
                                <span class="text-xs font-semibold text-[#1d1d1f]">QRIS</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="metode_bayar" value="Transfer" class="peer sr-only">
                            <div class="p-2.5 rounded-xl border border-[#d2d2d7] text-center peer-checked:border-[#0071e3] peer-checked:bg-[#e8f2ff] transition-all">
                                <i class="fas fa-building-columns text-xs block mb-1 text-[#86868b] peer-checked:text-[#0071e3]"></i>
                                <span class="text-xs font-semibold text-[#1d1d1f]">Transfer</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Diskon & Poin --}}
                <div class="space-y-3 mb-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-[#86868b] mb-1">Diskon Tambahan (Rp)</label>
                        <input type="number" name="diskon" id="diskonInput" class="apple-input text-xs" placeholder="0" min="0" oninput="kalkulasi()">
                    </div>

                    <div class="hidden" id="poinGroup">
                        <label class="block text-[11px] font-semibold text-[#1e7e34] mb-1">Gunakan Poin Member (Maks: <span id="maxPoin">0</span>)</label>
                        <input type="number" name="poin_digunakan" id="poinInput" class="apple-input text-xs border-[#c3f0d4]" placeholder="0" min="0" oninput="kalkulasi()">
                    </div>
                </div>

                {{-- Summary Calculation --}}
                <div class="p-4 rounded-xl bg-[#fafafc] border border-[#f0f0f0] space-y-2 text-xs mb-4">
                    <div class="flex justify-between text-[#86868b]">
                        <span>Subtotal</span>
                        <span class="font-semibold text-[#1d1d1f] tabular-nums">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[#e03131]">
                        <span>Potongan</span>
                        <span id="potonganDisplay" class="font-semibold tabular-nums">- Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-[#e5e5ea]">
                        <span class="font-bold text-[#1d1d1f]">Total Bayar</span>
                        <span id="totalDisplay" class="text-base font-bold text-[#0071e3] tabular-nums">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Cash Received Input --}}
                <div class="space-y-2 mb-4">
                    <label class="block text-xs font-semibold text-[#1d1d1f]">Jumlah Uang Diterima <span class="text-[#e03131]">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-[#86868b]">Rp</span>
                        <input type="number" name="bayar" id="bayarInput" class="apple-input pl-10 text-base font-bold tabular-nums" placeholder="0" required oninput="kalkulasi()">
                    </div>
                </div>

                {{-- Kembalian Box --}}
                <div class="p-3.5 rounded-xl bg-[#f5f5f7] flex items-center justify-between text-xs mb-5">
                    <span class="text-[#86868b] font-medium">Kembalian</span>
                    <span class="text-sm font-bold text-[#1d1d1f] tabular-nums" id="kembalianDisplay">Rp 0</span>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn-apple-primary w-full py-3 text-sm justify-center flex items-center gap-2" id="btnProses">
                    <i class="fas fa-check-circle"></i> Selesaikan Transaksi
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const subtotal = {{ $subtotal }};
    let poinTersedia = 0;

    function formatRp(angka) {
        return 'Rp ' + Math.max(0, parseInt(angka)).toLocaleString('id-ID');
    }

    async function cekMember() {
        const phone = document.getElementById('memberPhone').value;
        if(!phone) return alert('Masukkan nomor HP terlebih dahulu');

        const btn = document.getElementById('btnCekMember');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        try {
            const res = await fetch(`{{ route('kasir.cek_member') }}?phone=${phone}`);
            const data = await res.json();
            
            const resultBox = document.getElementById('memberResult');
            const poinGroup = document.getElementById('poinGroup');
            
            if(data.found) {
                document.getElementById('member_id').value = data.member.id;
                poinTersedia = data.member.points;
                document.getElementById('maxPoin').textContent = formatRp(poinTersedia);
                
                resultBox.classList.remove('hidden', 'bg-[#fff0f0]', 'border-[#ffc9c9]', 'text-[#e03131]');
                resultBox.classList.add('bg-[#e8f2ff]', 'border-[#cce4ff]', 'text-[#0071e3]');
                resultBox.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-bold text-[#1d1d1f]">${data.member.nama}</div>
                            <div class="text-[11px] text-[#0071e3] mt-0.5"><i class="fas fa-star mr-1"></i> Poin Tersedia: ${formatRp(poinTersedia)}</div>
                        </div>
                        <i class="fas fa-check-circle text-[#0071e3] text-lg"></i>
                    </div>
                `;
                
                poinGroup.classList.remove('hidden');
            } else {
                document.getElementById('member_id').value = '';
                poinTersedia = 0;
                poinGroup.classList.add('hidden');
                document.getElementById('poinInput').value = '';
                
                resultBox.classList.remove('hidden', 'bg-[#e8f2ff]', 'border-[#cce4ff]', 'text-[#0071e3]');
                resultBox.classList.add('bg-[#fff0f0]', 'border-[#ffc9c9]', 'text-[#e03131]');
                resultBox.innerHTML = `
                    <div class="font-bold">Member tidak ditemukan</div>
                    <div class="text-[11px] opacity-80 mt-0.5">Pastikan nomor HP yang dimasukkan sudah benar.</div>
                `;
            }
            kalkulasi();
        } catch (e) {
            alert('Gagal mengecek member');
        } finally {
            btn.innerHTML = 'Cek';
        }
    }

    function kalkulasi() {
        const diskonInput = document.getElementById('diskonInput').value;
        const poinInput = document.getElementById('poinInput').value;
        const bayarInput = document.getElementById('bayarInput').value;

        let diskon = parseFloat(diskonInput) || 0;
        let poin = parseFloat(poinInput) || 0;
        
        if (poin > poinTersedia) {
            poin = poinTersedia;
            document.getElementById('poinInput').value = poin;
        }

        const totalPotongan = diskon + poin;
        let total = subtotal - totalPotongan;
        if (total < 0) total = 0;

        const bayar = parseFloat(bayarInput) || 0;
        const kembalian = bayar - total;

        document.getElementById('potonganDisplay').textContent = '- ' + formatRp(totalPotongan);
        document.getElementById('totalDisplay').textContent = formatRp(total);
        
        const kembalianDisplay = document.getElementById('kembalianDisplay');
        if (bayar > 0 && kembalian >= 0) {
            kembalianDisplay.textContent = formatRp(kembalian);
            kembalianDisplay.classList.replace('text-[#1d1d1f]', 'text-[#1e7e34]');
        } else {
            kembalianDisplay.textContent = 'Rp 0';
            kembalianDisplay.classList.replace('text-[#1e7e34]', 'text-[#1d1d1f]');
        }

        const metode = document.querySelector('input[name="metode_bayar"]:checked').value;
        if(metode !== 'Tunai' && document.activeElement !== document.getElementById('bayarInput')) {
            document.getElementById('bayarInput').value = total;
            document.getElementById('kembalianDisplay').textContent = 'Rp 0';
        }
    }

    document.querySelectorAll('input[name="metode_bayar"]').forEach(radio => {
        radio.addEventListener('change', kalkulasi);
    });
</script>
@endpush
