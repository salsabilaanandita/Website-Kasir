@extends('layouts.app')
@section('title', 'Kasir / POS')

@push('styles')
<style>
    :root {
        --pos-bg: #f8fafc;
        --pos-surface: #ffffff;
        --pos-border: #e2e8f0;
        --pos-primary: #0f172a;
        --pos-primary-hover: #1e293b;
        --pos-text: #0f172a;
        --pos-text-muted: #64748b;
        --pos-radius-lg: 12px;
        --pos-radius-md: 8px;
        --pos-radius-sm: 6px;
    }

    /* POS Master Grid */
    .pos-grid-container { 
        display: grid; 
        grid-template-columns: 1fr 380px; 
        gap: 20px; 
        min-height: calc(100vh - 110px); 
        align-items: start;
    }

    /* Scrollbar */
    .pos-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
    .pos-scroll::-webkit-scrollbar-track { background: transparent; }
    .pos-scroll::-webkit-scrollbar-thumb { background: #d2d2d7; border-radius: 9999px; }
    .pos-scroll::-webkit-scrollbar-thumb:hover { background: #86868b; }

    /* Product Section */
    .product-section { 
        background: var(--pos-surface); 
        border: 1px solid var(--pos-border);
        border-radius: var(--pos-radius-lg); 
        display: flex; 
        flex-direction: column; 
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        min-height: calc(100vh - 110px);
    }

    .product-toolbar { 
        padding: 14px 18px; 
        border-bottom: 1px solid var(--pos-border); 
        display: flex;
        gap: 12px;
        align-items: center;
        background: #ffffff;
        flex-wrap: wrap;
    }
    
    .search-input-wrap {
        flex: 1;
        min-width: 220px;
        position: relative;
    }
    
    .search-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--pos-text-muted);
        font-size: 13px;
        pointer-events: none;
    }

    .pos-search-input { 
        width: 100%; 
        height: 40px;
        padding: 0 14px 0 38px; 
        background: #f5f5f7;
        border: 1px solid var(--pos-border); 
        border-radius: var(--pos-radius-md); 
        font-size: 13px; 
        font-weight: 400;
        outline: none; 
        color: var(--pos-text);
        transition: border-color 0.15s ease, background 0.15s ease; 
    }
    
    .pos-search-input:focus { 
        background: #ffffff;
        border-color: var(--pos-primary); 
        box-shadow: 0 0 0 2px rgba(15, 23, 42, 0.12); 
    }

    .product-catalog-grid { 
        padding: 18px; 
        overflow-y: auto; 
        flex: 1; 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); 
        gap: 14px; 
        align-content: start; 
        max-height: calc(100vh - 190px);
    }
    
    .pos-product-card { 
        background: #ffffff;
        border: 1px solid var(--pos-border); 
        border-radius: var(--pos-radius-md); 
        overflow: hidden; 
        cursor: pointer; 
        transition: border-color 0.15s ease, transform 0.15s ease; 
        position: relative;
        display: flex;
        flex-direction: column;
        user-select: none;
    }

    .pos-product-card:hover { 
        border-color: #a1a1a6;
        transform: translateY(-2px); 
    }
    
    .pos-product-card:active { 
        transform: scale(0.97); 
    }

    .pos-product-card.out-of-stock { 
        opacity: 0.5; 
        cursor: not-allowed; 
        filter: grayscale(1);
    }
    
    .pos-product-card.out-of-stock:hover { 
        transform: none; 
        border-color: var(--pos-border); 
    }

    .product-thumbnail-box {
        position: relative;
        padding-top: 85%;
        background: #f5f5f7;
        overflow: hidden;
    }

    .pos-product-card img, 
    .pos-product-card .no-image-placeholder { 
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover; 
    }
    
    .pos-product-card .no-image-placeholder { 
        display: flex; align-items: center; justify-content: center; 
        font-size: 24px; color: #d2d2d7; 
        background: #f5f5f7;
    }

    .pos-stock-badge { 
        position: absolute; 
        top: 6px; 
        right: 6px;
        font-size: 10px; 
        font-weight: 600;
        padding: 2px 7px; 
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.95);
        color: #515154;
        border: 1px solid rgba(0,0,0,0.06);
    }
    
    .stock-low { color: #b45309; background: #fef3c7; }
    .stock-out { color: #d70015; background: #ffe5e5; }

    .product-meta-body { 
        padding: 10px 12px; 
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
    }

    .product-name-title { 
        font-size: 12.5px; 
        font-weight: 600; 
        color: var(--pos-text); 
        margin-bottom: 4px; 
        line-height: 1.35; 
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical; 
        overflow: hidden; 
    }

    .product-price-label { 
        font-size: 13.5px; 
        font-weight: 600; 
        color: var(--pos-primary); 
    }

    /* Cart Section */
    .cart-section { 
        background: #ffffff; 
        border-radius: var(--pos-radius-lg); 
        border: 1px solid var(--pos-border);
        display: flex; 
        flex-direction: column; 
        overflow: hidden;
        position: sticky;
        top: 80px;
        height: calc(100vh - 110px);
    }

    .cart-header-bar { 
        padding: 14px 18px; 
        border-bottom: 1px solid var(--pos-border); 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        background: #ffffff;
    }
    
    .cart-header-bar h3 { 
        font-weight: 600; 
        font-size: 14px; 
        color: var(--pos-text); 
        display: flex; 
        align-items: center; 
        gap: 8px; 
        margin: 0; 
    }

    .cart-counter-pill { 
        background: #f5f5f7; 
        color: var(--pos-primary); 
        font-size: 11.5px; 
        font-weight: 600; 
        padding: 3px 8px; 
        border-radius: 9999px; 
    }

    .cart-items-wrapper { 
        flex: 1; 
        overflow-y: auto; 
        padding: 12px 14px; 
        display: flex; 
        flex-direction: column; 
        gap: 8px; 
    }
    
    .cart-single-item { 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        padding: 8px 10px; 
        background: #fbfbfd;
        border: 1px solid var(--pos-border); 
        border-radius: var(--pos-radius-md); 
        position: relative;
    }
    
    .cart-single-item img, 
    .cart-single-item .cart-thumb-placeholder { 
        width: 40px; 
        height: 40px; 
        object-fit: cover; 
        border-radius: var(--pos-radius-sm); 
        background: #ffffff; 
        border: 1px solid #e5e5ea;
        flex-shrink: 0;
    }
    
    .cart-single-item .cart-thumb-placeholder { 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: #86868b; 
        font-size: 12px;
    }
    
    .cart-item-details { 
        flex: 1; 
        min-width: 0; 
    }

    .cart-item-title { 
        font-size: 12px; 
        font-weight: 600; 
        color: var(--pos-text); 
        white-space: nowrap; 
        overflow: hidden; 
        text-overflow: ellipsis; 
        margin-bottom: 2px; 
    }

    .cart-item-unit-price { 
        font-size: 11px; 
        color: var(--pos-text-muted); 
    }
    
    .cart-qty-control { 
        display: flex; 
        align-items: center; 
        background: #ffffff; 
        border-radius: 6px; 
        border: 1px solid #d2d2d7; 
        padding: 1px; 
    }

    .qty-btn-action { 
        width: 24px; 
        height: 24px; 
        background: #ffffff; 
        border: none; 
        border-radius: 4px; 
        cursor: pointer; 
        font-size: 13px; 
        font-weight: 600; 
        color: var(--pos-text);
        display: flex; 
        align-items: center; 
        justify-content: center; 
        transition: background 0.15s; 
    }

    .qty-btn-action:hover { 
        background: #f5f5f7; 
        color: var(--pos-primary); 
    }

    .qty-indicator { 
        font-size: 11.5px; 
        font-weight: 600; 
        width: 24px; 
        text-align: center; 
        color: var(--pos-text); 
    }
    
    .cart-item-total { 
        font-size: 12.5px; 
        font-weight: 600; 
        color: var(--pos-text); 
        white-space: nowrap; 
        text-align: right; 
        min-width: 65px; 
    }
    
    .cart-remove-button { 
        width: 22px; 
        height: 22px; 
        border-radius: 4px;
        background: transparent; 
        border: none; 
        color: #86868b; 
        cursor: pointer; 
        font-size: 11px; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        transition: color 0.15s; 
    }
    
    .cart-remove-button:hover { 
        color: #d70015; 
    }

    /* Empty Cart State */
    .empty-cart-state { 
        height: 100%; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        text-align: center; 
        color: #86868b; 
        padding: 24px; 
    }

    .empty-cart-state i { 
        font-size: 36px; 
        color: #d2d2d7; 
        margin-bottom: 12px; 
    }

    .empty-cart-state h4 { 
        font-size: 14px; 
        font-weight: 600; 
        color: #1d1d1f; 
        margin-bottom: 4px; 
    }

    .empty-cart-state p { 
        font-size: 12px; 
        line-height: 1.4;
    }

    /* Cart Summary & Checkout */
    .cart-checkout-footer { 
        padding: 14px 18px; 
        border-top: 1px solid var(--pos-border); 
        background: #ffffff; 
    }
    
    .summary-item-row { 
        display: flex; 
        justify-content: space-between; 
        font-size: 12.5px; 
        margin-bottom: 6px; 
        color: var(--pos-text-muted); 
    }

    .summary-item-row.total-amount { 
        font-weight: 600; 
        font-size: 18px; 
        color: var(--pos-text); 
        border-top: 1px solid var(--pos-border); 
        padding-top: 10px; 
        margin-top: 6px; 
        margin-bottom: 14px;
        align-items: center;
    }

    .summary-item-row.total-amount span:last-child { 
        color: var(--pos-primary); 
    }
    
    .btn-pos-checkout { 
        width: 100%; 
        height: 44px;
        background-color: var(--pos-primary); 
        color: #ffffff; 
        border: none; 
        border-radius: var(--pos-radius-md); 
        font-size: 13.5px; 
        font-weight: 500; 
        cursor: pointer; 
        transition: background-color 0.15s ease, transform 0.1s ease; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 8px;
    }
    
    .btn-pos-checkout:hover:not(:disabled) { 
        background-color: var(--pos-primary-hover); 
    }

    .btn-pos-checkout:active:not(:disabled) { 
        transform: scale(0.98); 
    }

    .btn-pos-checkout:disabled { 
        background: #d2d2d7; 
        cursor: not-allowed; 
        opacity: 0.7; 
    }
    
    .cart-quick-actions { 
        display: flex; 
        gap: 8px; 
        margin-top: 8px; 
    }
    
    .btn-action-ghost {
        flex: 1; 
        padding: 6px; 
        background: #f5f5f7; 
        border: 1px solid var(--pos-border); 
        border-radius: 8px;
        font-size: 11.5px; 
        font-weight: 500; 
        color: #515154; 
        cursor: pointer; 
        transition: background 0.15s;
    }

    .btn-action-ghost:hover { 
        background: #ffe5e5; 
        color: #d70015; 
        border-color: #ffccd0; 
    }

    /* Mobile Bottom Bar */
    .mobile-cart-bar {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        border-top: 1px solid var(--pos-border);
        padding: 10px 16px;
        z-index: 1040;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    }

    /* Shift Warning */
    .shift-alert-banner { 
        background: #fffbeb; 
        border: 1px solid #fde68a; 
        border-radius: var(--pos-radius-md); 
        padding: 10px 16px; 
        margin-bottom: 16px; 
        font-size: 13px; 
        font-weight: 500; 
        color: #92400e; 
        display: flex; 
        align-items: center; 
        justify-content: space-between;
        gap: 12px; 
        flex-wrap: wrap;
    }

    /* Responsive */
    @media (max-width: 1023px) {
        .pos-grid-container { 
            grid-template-columns: 1fr; 
            min-height: auto;
            margin-bottom: 70px;
        }

        .product-section {
            min-height: auto;
        }

        .product-catalog-grid {
            max-height: none;
        }

        .cart-section { 
            position: fixed;
            inset: 0;
            top: auto;
            bottom: 0;
            height: 80vh;
            border-radius: 20px 20px 0 0;
            z-index: 1060;
            transform: translateY(100%);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 -8px 30px rgba(0,0,0,0.15);
        }

        .cart-section.mobile-open {
            transform: translateY(0);
        }

        .mobile-cart-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .mobile-cart-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
            z-index: 1055;
        }

        .mobile-cart-backdrop.show {
            display: block;
        }

        .cart-mobile-close-btn {
            display: flex !important;
        }
    }

    .cart-mobile-close-btn {
        display: none;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: #f5f5f7;
        border: none;
        align-items: center;
        justify-content: center;
        color: #86868b;
        cursor: pointer;
    }
</style>
@endpush

@section('content')

{{-- Shift Warning --}}
@if(!$activeShift)
<div class="shift-alert-banner">
    <div class="flex items-center gap-2.5">
        <i class="fas fa-exclamation-triangle text-amber-600"></i>
        <span><strong>Perhatian:</strong> Anda belum membuka shift kasir hari ini.</span>
    </div>
    <a href="{{ route('shifts.open') }}" class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-medium no-underline">
        Buka Shift &rarr;
    </a>
</div>
@endif

<div class="pos-grid-container">
    {{-- Product Catalog --}}
    <div class="product-section">
        <div class="product-toolbar">
            <div class="search-input-wrap">
                <i class="fas fa-search"></i>
                <input 
                    type="text" 
                    id="productSearch" 
                    class="pos-search-input" 
                    placeholder="Cari nama produk..." 
                    oninput="filterProducts(this.value)"
                    autofocus
                >
            </div>
            <div class="text-xs text-[#86868b] bg-[#f5f5f7] px-3 py-2 rounded-lg hidden sm:flex items-center gap-1.5">
                <i class="fas fa-box text-xs"></i>
                <span>{{ count($products) }} Produk</span>
            </div>
        </div>
        
        <div class="product-catalog-grid pos-scroll" id="productGrid">
            @forelse($products as $product)
            @php
                $stockClass = '';
                $stockLabel = 'Stok: ' . $product->stok;
                if ($product->stok == 0) { 
                    $stockClass = 'stock-out'; 
                    $stockLabel = 'Habis'; 
                } elseif ($product->stok <= 5) { 
                    $stockClass = 'stock-low'; 
                }
            @endphp
            
            <div class="pos-product-card {{ $product->stok == 0 ? 'out-of-stock' : '' }}" 
                 onclick="{{ $product->stok > 0 ? 'addToCart(' . $product->id . ')' : '' }}"
                 data-name="{{ strtolower($product->nama_produk) }}"
                 id="card-{{ $product->id }}">
                
                <div class="product-thumbnail-box">
                    @if($product->img)
                        <img src="{{ asset('storage/' . $product->img) }}" alt="{{ $product->nama_produk }}" loading="lazy">
                    @else
                        <div class="no-image-placeholder"><i class="fas fa-box"></i></div>
                    @endif
                    <div class="pos-stock-badge {{ $stockClass }}">{{ $stockLabel }}</div>
                </div>
                
                <div class="product-meta-body">
                    <div class="product-name-title" title="{{ $product->nama_produk }}">{{ $product->nama_produk }}</div>
                    <div class="product-price-label">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 50px 20px; color: #86868b;">
                <i class="fas fa-box-open" style="font-size: 40px; color: #d2d2d7; margin-bottom: 10px;"></i>
                <h4 class="text-sm font-semibold text-[#1d1d1f] mb-1">Katalog Kosong</h4>
                <p class="text-xs">Belum ada produk yang tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Mobile Cart Backdrop --}}
    <div id="mobileCartBackdrop" class="mobile-cart-backdrop" onclick="toggleMobileCart(false)"></div>

    {{-- Cart Section --}}
    <div class="cart-section" id="cartDrawer">
        <div class="cart-header-bar">
            <h3>
                <i class="fas fa-shopping-bag text-slate-800"></i>
                <span>Pesanan</span>
            </h3>
            <div class="flex items-center gap-2">
                <span class="cart-counter-pill" id="cartCount">0 item</span>
                <button class="cart-mobile-close-btn" onclick="toggleMobileCart(false)" aria-label="Tutup">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="cart-items-wrapper pos-scroll" id="cartItems">
            <div class="empty-cart-state">
                <i class="fas fa-shopping-cart"></i>
                <h4>Keranjang Kosong</h4>
                <p>Pilih produk di katalog untuk ditambahkan ke pesanan.</p>
            </div>
        </div>
        
        <div class="cart-checkout-footer">
            <div class="summary-item-row">
                <span>Subtotal</span>
                <span id="subtotalDisplay" class="font-medium text-slate-900">Rp 0</span>
            </div>
            <div class="summary-item-row total-amount">
                <span>Total</span>
                <span id="totalDisplay">Rp 0</span>
            </div>
            
            <button class="btn-pos-checkout" id="checkoutBtn" disabled onclick="goCheckout()">
                <span>Bayar Sekarang</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </button>
            
            <div class="cart-quick-actions">
                <button class="btn-action-ghost" onclick="clearCart()">
                    <i class="fas fa-trash-alt mr-1"></i> Kosongkan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Mobile Sticky Cart Bar --}}
<div class="mobile-cart-bar">
    <div class="flex flex-col">
        <span class="text-[11px] text-slate-500" id="mobileItemCount">0 Item</span>
        <span class="text-sm font-bold text-slate-900" id="mobileTotalDisplay">Rp 0</span>
    </div>
    <button onclick="toggleMobileCart(true)" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs rounded-lg border-0 cursor-pointer flex items-center gap-1.5 transition-colors">
        <i class="fas fa-shopping-bag text-xs"></i>
        <span>Lihat Pesanan</span>
    </button>
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function formatRp(n) {
    return 'Rp ' + parseInt(n || 0).toLocaleString('id-ID');
}

function toggleMobileCart(open) {
    const drawer = document.getElementById('cartDrawer');
    const backdrop = document.getElementById('mobileCartBackdrop');
    if (open) {
        drawer.classList.add('mobile-open');
        backdrop.classList.add('show');
        document.body.classList.add('overflow-hidden');
    } else {
        drawer.classList.remove('mobile-open');
        backdrop.classList.remove('show');
        document.body.classList.remove('overflow-hidden');
    }
}

function filterProducts(q) {
    const cards = document.querySelectorAll('.pos-product-card');
    cards.forEach(card => {
        if(card.dataset.name.includes(q.toLowerCase())) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function playClickEffect(cardId) {
    const card = document.getElementById('card-' + cardId);
    if(card) {
        card.style.transform = 'scale(0.96)';
        setTimeout(() => { card.style.transform = ''; }, 100);
    }
}

async function addToCart(productId) {
    playClickEffect(productId);
    try {
        const res = await fetch('{{ route("kasir.cart.add") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ product_id: productId, qty: 1 })
        });
        const data = await res.json();
        if (data.success) {
            loadCart();
        } else {
            alert(data.message || 'Gagal menambahkan produk');
        }
    } catch(err) {
        console.error(err);
    }
}

async function loadCart() {
    try {
        const res  = await fetch('{{ route("kasir.cart.get") }}');
        const data = await res.json();
        renderCart(data);
    } catch(err) {
        console.error(err);
    }
}

function renderCart(data) {
    const items    = data.items || [];
    const subtotal = data.subtotal || 0;
    const count    = data.cart_count || 0;

    document.getElementById('cartCount').textContent = count + ' item';
    document.getElementById('mobileItemCount').textContent = count + ' Item';
    document.getElementById('subtotalDisplay').textContent = formatRp(subtotal);
    document.getElementById('totalDisplay').textContent = formatRp(subtotal);
    document.getElementById('mobileTotalDisplay').textContent = formatRp(subtotal);
    document.getElementById('checkoutBtn').disabled = count === 0;

    const container = document.getElementById('cartItems');
    
    if (items.length === 0) {
        container.innerHTML = `
            <div class="empty-cart-state">
                <i class="fas fa-shopping-cart"></i>
                <h4>Keranjang Kosong</h4>
                <p>Pilih produk di katalog untuk ditambahkan ke pesanan.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = items.map(item => `
        <div class="cart-single-item">
            ${item.img 
                ? `<img src="${item.img}" alt="${item.nama}">` 
                : `<div class="cart-thumb-placeholder"><i class="fas fa-box"></i></div>`
            }
            <div class="cart-item-details">
                <div class="cart-item-title" title="${item.nama}">${item.nama}</div>
                <div class="cart-item-unit-price">${formatRp(item.harga)}</div>
            </div>
            
            <div class="cart-qty-control">
                <button class="qty-btn-action" onclick="updateQty(${item.product_id}, ${item.qty - 1})">&minus;</button>
                <div class="qty-indicator">${item.qty}</div>
                <button class="qty-btn-action" onclick="updateQty(${item.product_id}, ${item.qty + 1})">&plus;</button>
            </div>
            
            <div class="cart-item-total">${formatRp(item.subtotal)}</div>
            
            <button class="cart-remove-button" onclick="removeItem(${item.product_id})" title="Hapus">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');
}

async function updateQty(productId, qty) {
    try {
        const res = await fetch('{{ route("kasir.cart.update") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ product_id: productId, qty })
        });
        const data = await res.json();
        if (data.success) loadCart();
        else alert(data.message || 'Gagal mengubah kuantitas');
    } catch(err) {
        console.error(err);
    }
}

async function removeItem(productId) {
    try {
        await fetch('{{ route("kasir.cart.remove") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ product_id: productId })
        });
        loadCart();
    } catch(err) {
        console.error(err);
    }
}

async function clearCart() {
    if (!confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) return;
    try {
        const res  = await fetch('{{ route("kasir.cart.get") }}');
        const data = await res.json();
        for (const item of (data.items || [])) {
            await fetch('{{ route("kasir.cart.remove") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ product_id: item.product_id })
            });
        }
        loadCart();
    } catch(err) {
        console.error(err);
    }
}

function goCheckout() {
    window.location.href = '{{ route("kasir.checkout") }}';
}

document.addEventListener('DOMContentLoaded', loadCart);
</script>
@endpush
