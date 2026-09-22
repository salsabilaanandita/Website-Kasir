<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
    <meta name="theme-color" content="#0071e3">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\Setting::get('nama_toko', 'Kasir App') }} — @yield('title', 'Dashboard')</title>

    <!-- Google Fonts Inter for non-macOS fallbacks -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Bootstrap CSS for grid & dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['-apple-system', 'BlinkMacSystemFont', '"SF Pro Text"', '"SF Pro Display"', '"Helvetica Neue"', '"Inter"', 'sans-serif'],
                    },
                    colors: {
                        apple: {
                            blue: '#0071e3',
                            'blue-hover': '#0077ed',
                            'blue-active': '#0062c4',
                            ink: '#1d1d1f',
                            muted: '#86868b',
                            border: '#d2d2d7',
                            'border-light': '#e5e5ea',
                            bg: '#f5f5f7',
                            card: '#ffffff',
                            danger: '#d70015',
                            warning: '#ff9500',
                            success: '#34c759'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --apple-blue: #0071e3;
            --apple-blue-hover: #0077ed;
            --apple-blue-active: #0062c4;
            --apple-ink: #1d1d1f;
            --apple-muted: #86868b;
            --apple-border: #d2d2d7;
            --apple-border-light: #e5e5ea;
            --apple-bg: #f5f5f7;
            --apple-card: #ffffff;
            --apple-danger: #d70015;
            --apple-warning: #ff9500;
            --apple-success: #34c759;
            --apple-radius-pill: 9999px;
            --apple-radius-lg: 16px;
            --apple-radius-md: 12px;
            --apple-radius-sm: 8px;
        }

        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Text', 'SF Pro Display', 'Helvetica Neue', 'Inter', sans-serif; 
            background-color: var(--apple-bg);
            color: var(--apple-ink);
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.015em;
        }

        /* Apple Component: Buttons */
        .btn-apple-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background-color: var(--apple-blue);
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 18px;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.15s ease, transform 0.1s ease;
        }
        .btn-apple-primary:hover { background-color: var(--apple-blue-hover); }
        .btn-apple-primary:active { transform: scale(0.97); background-color: var(--apple-blue-active); }

        .btn-apple-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background-color: #ffffff;
            color: var(--apple-blue) !important;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 18px;
            border-radius: 9999px;
            border: 1px solid var(--apple-border);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-apple-secondary:hover { border-color: var(--apple-blue); background-color: #f5f5f7; }
        .btn-apple-secondary:active { transform: scale(0.97); }

        .btn-apple-dark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background-color: var(--apple-ink);
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-apple-dark:hover { background-color: #333336; }
        .btn-apple-dark:active { transform: scale(0.97); }

        .btn-apple-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background-color: #f5f5f7;
            color: #424245 !important;
            font-size: 12.5px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid var(--apple-border-light);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-apple-ghost:hover { background-color: #e5e5ea; color: var(--apple-ink) !important; }
        .btn-apple-ghost:active { transform: scale(0.97); }

        .btn-apple-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background-color: #fff2f2;
            color: var(--apple-danger) !important;
            font-size: 12.5px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid #ffccd0;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-apple-danger:hover { background-color: var(--apple-danger); color: #ffffff !important; }
        .btn-apple-danger:active { transform: scale(0.97); }

        /* Apple Component: Card */
        .apple-card {
            background-color: #ffffff;
            border: 1px solid var(--apple-border-light);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        /* Apple Component: Table */
        .apple-table {
            width: 100%;
            border-collapse: collapse;
        }
        .apple-table th {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--apple-muted);
            padding: 12px 16px;
            border-bottom: 1px solid var(--apple-border-light);
            background-color: #fcfcfd;
            text-align: left;
        }
        .apple-table td {
            font-size: 13px;
            color: var(--apple-ink);
            padding: 12px 16px;
            border-bottom: 1px solid #f2f2f7;
            vertical-align: middle;
        }
        .apple-table tr:hover td {
            background-color: #fbfbfd;
        }

        /* Apple Component: Input */
        .apple-input {
            width: 100%;
            height: 38px;
            padding: 0 12px;
            font-size: 13.5px;
            font-family: inherit;
            color: var(--apple-ink);
            background-color: #ffffff;
            border: 1px solid var(--apple-border);
            border-radius: 10px;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .apple-input:focus {
            border-color: var(--apple-blue);
            box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.15);
        }

        /* Apple Component: Badge */
        .apple-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 500;
            padding: 2.5px 8px;
            border-radius: 9999px;
            background-color: #f5f5f7;
            color: #515154;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .apple-badge-blue { background-color: #f0f6ff; color: #0071e3; border-color: #cce0ff; }
        .apple-badge-green { background-color: #f0fdf4; color: #16a34a; border-color: #dcfce7; }
        .apple-badge-amber { background-color: #fffbeb; color: #d97706; border-color: #fef3c7; }
        .apple-badge-red { background-color: #fff2f2; color: #d70015; border-color: #ffccd0; }

        /* Apple Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d2d2d7; border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #86868b; }

        /* Mobile drawer */
        @media (max-width: 1023px) {
            .sidebar-drawer { 
                transform: translateX(-100%); 
                transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1); 
            }
            .sidebar-drawer.show { 
                transform: translateX(0); 
            }
        }

        /* Dropdown Clean */
        .dropdown-menu {
            border: 1px solid var(--apple-border) !important;
            border-radius: 14px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-[#f5f5f7] text-[#1d1d1f] min-h-screen flex flex-col antialiased">
    <div id="wrapper" class="flex flex-1 min-h-screen w-full relative">
        {{-- Mobile Overlay --}}
        <div id="sidebarOverlay" onclick="closeSidebar()" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[1045] hidden transition-opacity"></div>

        {{-- Sidebar --}}
        <aside id="sidebar" class="sidebar-drawer fixed lg:sticky top-0 left-0 h-screen w-[260px] bg-white border-r border-[#e5e5ea] z-[1050] flex flex-col custom-scrollbar overflow-y-auto flex-shrink-0">
            
            {{-- Brand Header --}}
            <div class="h-[64px] px-5 flex items-center justify-between border-b border-[#e5e5ea] flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 no-underline">
                    <div class="w-8 h-8 bg-[#1d1d1f] text-white rounded-lg flex items-center justify-center text-sm">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-[#1d1d1f] leading-tight">
                            {{ \App\Models\Setting::get('nama_toko', 'Kasir App') }}
                        </span>
                        <span class="text-[11px] text-[#86868b] leading-tight capitalize">
                            Role: {{ Auth::user()->role }}
                        </span>
                    </div>
                </a>
                <button onclick="closeSidebar()" class="lg:hidden p-1.5 text-[#86868b] hover:text-[#1d1d1f] rounded-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Navigation Menu --}}
            <nav class="flex-grow p-3 space-y-1">
                {{-- Quick POS Shortcut --}}
                <div class="mb-3">
                    <a href="{{ route('kasir.index') }}" class="btn-apple-primary w-full py-2 text-xs font-semibold">
                        <i class="fas fa-calculator text-xs"></i>
                        <span>Buka Kasir / POS</span>
                    </a>
                </div>

                {{-- Group: Utama --}}
                <div class="px-3 pt-2 pb-1 text-[11px] font-semibold text-[#86868b] uppercase tracking-wider">Utama</div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('dashboard*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                    <i class="fas fa-th-large w-4 text-center text-sm {{ Request::routeIs('dashboard*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('kasir.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('kasir.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                    <i class="fas fa-calculator w-4 text-center text-sm {{ Request::routeIs('kasir.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                    <span>Kasir / POS</span>
                </a>
                <a href="{{ route('penjualan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('penjualan.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                    <i class="fas fa-receipt w-4 text-center text-sm {{ Request::routeIs('penjualan.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                    <span>Riwayat Penjualan</span>
                </a>

                {{-- Group: Produk & Stok --}}
                <div class="px-3 pt-4 pb-1 text-[11px] font-semibold text-[#86868b] uppercase tracking-wider">Produk</div>
                
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('products*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                    <i class="fas fa-box w-4 text-center text-sm {{ Request::routeIs('products*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                    <span>Produk</span>
                </a>

                {{-- Categories, Inventory, Suppliers ONLY for Admin & Manager --}}
                @if(in_array(Auth::user()->role, ['admin', 'manager']))
                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('categories.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                        <i class="fas fa-tags w-4 text-center text-sm {{ Request::routeIs('categories.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                        <span>Kategori</span>
                    </a>
                    <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('inventory.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                        <i class="fas fa-warehouse w-4 text-center text-sm {{ Request::routeIs('inventory.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                        <span>Inventory</span>
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('suppliers.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                        <i class="fas fa-truck w-4 text-center text-sm {{ Request::routeIs('suppliers.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                        <span>Supplier</span>
                    </a>
                @endif

                {{-- Group: Operasional --}}
                <div class="px-3 pt-4 pb-1 text-[11px] font-semibold text-[#86868b] uppercase tracking-wider">Operasional</div>

                {{-- Expenses ONLY for Admin & Manager --}}
                @if(in_array(Auth::user()->role, ['admin', 'manager']))
                    <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('expenses.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                        <i class="fas fa-money-bill-wave w-4 text-center text-sm {{ Request::routeIs('expenses.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                        <span>Pengeluaran</span>
                    </a>
                @endif

                <a href="{{ route('shifts.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('shifts.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                    <i class="fas fa-clock w-4 text-center text-sm {{ Request::routeIs('shifts.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                    <span>Shift</span>
                </a>
                <a href="{{ route('returns.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('returns.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                    <i class="fas fa-undo-alt w-4 text-center text-sm {{ Request::routeIs('returns.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                    <span>Return</span>
                </a>

                {{-- Laporan ONLY for Admin & Manager --}}
                @if(in_array(Auth::user()->role, ['admin', 'manager']))
                    <div class="px-3 pt-4 pb-1 text-[11px] font-semibold text-[#86868b] uppercase tracking-wider">Laporan</div>
                    <a href="{{ route('reports.sales') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('reports.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                        <i class="fas fa-chart-line w-4 text-center text-sm {{ Request::routeIs('reports.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                        <span>Laporan</span>
                    </a>
                @endif

                {{-- Admin Menu ONLY for Admin --}}
                @if(Auth::user()->role === 'admin')
                    <div class="px-3 pt-4 pb-1 text-[11px] font-semibold text-[#86868b] uppercase tracking-wider">Admin</div>
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('users.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                        <i class="fas fa-users w-4 text-center text-sm {{ Request::routeIs('users.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('roles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('roles.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                        <i class="fas fa-shield-alt w-4 text-center text-sm {{ Request::routeIs('roles.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                        <span>Roles</span>
                    </a>
                    <a href="{{ route('settings.store') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('settings.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                        <i class="fas fa-cog w-4 text-center text-sm {{ Request::routeIs('settings.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="{{ route('activity.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors no-underline {{ Request::routeIs('activity.*') ? 'bg-[#f5f5f7] text-[#0071e3] font-semibold' : 'text-[#424245] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                        <i class="fas fa-history w-4 text-center text-sm {{ Request::routeIs('activity.*') ? 'text-[#0071e3]' : 'text-[#86868b]' }}"></i>
                        <span>Log Aktivitas</span>
                    </a>
                @endif

                <div class="px-3 pt-4 pb-1 text-[11px] font-semibold text-[#86868b] uppercase tracking-wider">Akun</div>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-[#d70015] hover:bg-[#fff2f2] transition-colors border-0 bg-transparent text-left cursor-pointer">
                        <i class="fas fa-sign-out-alt w-4 text-center text-sm"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        {{-- Main Area --}}
        <div class="flex-grow flex flex-col min-h-screen w-full overflow-x-hidden">
            {{-- Header (Apple Frosted Bar) --}}
            <header class="h-[64px] bg-white/80 backdrop-blur-md border-b border-[#e5e5ea] sticky top-0 z-[1040] px-4 sm:px-6 flex items-center justify-between gap-4">
                
                {{-- Left: Mobile Burger & Page Title --}}
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-[#d2d2d7] text-[#1d1d1f] hover:bg-[#f5f5f7] transition-colors" aria-label="Buka Menu">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="flex items-center gap-2 text-xs text-[#86868b]">
                        <span class="hidden sm:inline">KasirApp</span>
                        <span class="hidden sm:inline">/</span>
                        <span class="text-[#1d1d1f] font-semibold text-sm capitalize">@yield('title', 'Dashboard')</span>
                    </div>
                </div>

                {{-- Right: Search & User Dropdown --}}
                <div class="flex items-center gap-3">
                    {{-- Global Search --}}
                    <form action="{{ route('search.global') }}" method="GET" class="relative hidden sm:block w-48 md:w-64">
                        <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-[#86868b] hover:text-[#0071e3] transition-colors" aria-label="Cari">
                            <i class="fas fa-search text-xs"></i>
                        </button>
                        <input 
                            type="text" 
                            name="query" 
                            placeholder="Cari produk atau invoice..." 
                            value="{{ request('query') }}"
                            class="w-full pl-8 pr-3 py-1.5 bg-[#f5f5f7] border border-[#e5e5ea] rounded-full text-xs font-normal focus:bg-white focus:outline-none focus:border-[#0071e3] focus:ring-2 focus:ring-[#0071e3]/15 transition-all placeholder:text-[#86868b]"
                        >
                    </form>

                    {{-- User Dropdown --}}
                    <div class="dropdown">
                        <button class="flex items-center gap-2.5 p-1 rounded-full hover:bg-[#f5f5f7] transition-colors border-0 bg-transparent cursor-pointer" data-bs-toggle="dropdown" aria-label="Menu Pengguna">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-semibold text-[#1d1d1f] leading-none mb-0.5">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-[#86868b] capitalize leading-none">{{ Auth::user()->role }}</p>
                            </div>
                            <div class="w-8 h-8 bg-[#1d1d1f] text-white rounded-full flex items-center justify-center font-semibold text-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2 border border-[#d2d2d7] shadow-lg rounded-xl mt-1.5 w-48">
                            <li class="px-3 py-2 border-b border-[#e5e5ea]">
                                <p class="text-xs font-semibold text-[#1d1d1f] m-0">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-[#86868b] m-0 truncate">{{ Auth::user()->email }}</p>
                            </li>
                            <li>
                                <a href="{{ route('dashboard') }}" class="dropdown-item rounded-lg py-1.5 text-xs text-[#1d1d1f] hover:bg-[#f5f5f7] flex items-center gap-2">
                                    <i class="fas fa-th-large text-[#86868b] w-4"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kasir.index') }}" class="dropdown-item rounded-lg py-1.5 text-xs text-[#1d1d1f] hover:bg-[#f5f5f7] flex items-center gap-2">
                                    <i class="fas fa-calculator text-[#86868b] w-4"></i> Kasir / POS
                                </a>
                            </li>
                            <li><hr class="dropdown-divider border-[#e5e5ea] my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-lg py-1.5 text-[#d70015] text-xs hover:bg-[#fff2f2] flex items-center gap-2 w-full text-left">
                                        <i class="fas fa-sign-out-alt w-4"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            {{-- Main Content Container --}}
            <main class="p-4 sm:p-6 lg:p-8 flex-1">
                @if(session('success'))
                    <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center justify-between text-xs font-medium">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-check-circle text-emerald-600"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button class="text-emerald-600 hover:text-emerald-900 border-0 bg-transparent p-1 cursor-pointer" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center justify-between text-xs font-medium">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-exclamation-circle text-rose-600"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button class="text-rose-600 hover:text-rose-900 border-0 bg-transparent p-1 cursor-pointer" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('show');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>