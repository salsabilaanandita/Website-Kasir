<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
    <meta name="theme-color" content="#3B82F6">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir - @yield('title', 'Dashboard')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Custom scrollbar untuk sidebar agar tetap cantik */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        /* Transition untuk mobile sidebar */
        @media (max-width: 768px) {
            .sidebar-mobile { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar-mobile.show { transform: translateX(0); }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    <div id="wrapper" class="flex">
        <div id="sidebarOverlay" onclick="closeSidebar()" class="fixed inset-0 bg-black/50 z-[1045] hidden"></div>

        <aside id="sidebar" class="sidebar-mobile fixed md:sticky top-0 left-0 h-screen w-[280px] bg-white border-r border-slate-200 z-[1050] flex flex-col custom-scrollbar overflow-y-auto shadow-sm">
            
            <div class="h-[72px] px-6 flex items-center border-b border-slate-100 flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 no-underline group">
                    <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-sm shadow-blue-200 transition-transform group-hover:scale-105">
                        <i class="fas fa-bars-staggered text-lg"></i>
                    </div>
                    <span class="text-xl font-bold text-slate-800 tracking-tight">KasirApp</span>
                </a>
            </div>

            <nav class="flex-grow p-4 space-y-1">
                <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Main Menu</div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ Request::routeIs('dashboard*') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <i class="fas fa-chart-pie w-5 text-center text-lg"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ Request::routeIs('products*') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <i class="fas fa-box w-5 text-center text-lg"></i>
                    <span>Products</span>
                </a>

                <a href="{{ route('pembelian.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ Request::routeIs('pembelian*') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <i class="fas fa-shopping-cart w-5 text-center text-lg"></i>
                    <span>Transactions</span>
                </a>

                @if(Auth::user()->role == 'admin')
                    <div class="px-3 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Management</div>
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ Request::routeIs('users*') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <i class="fas fa-users w-5 text-center text-lg"></i>
                        <span>Users</span>
                    </a>
                @endif

                <div class="px-3 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Account</div>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition-all">
                        <i class="fas fa-sign-out-alt w-5 text-center text-lg"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <div class="flex-grow flex flex-col min-h-screen">
            <header class="h-[72px] bg-white border-b border-slate-200 sticky top-0 z-[1040] px-4 md:px-8 flex items-center justify-between gap-4">
                
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="hidden md:flex items-center gap-2 text-xs text-slate-400">
                        <span class="font-semibold text-slate-800 uppercase tracking-wide capitalize">Pages</span>
                        <span>/</span>
                        <span class="font-semibold text-slate-800 uppercase tracking-wide capitalize">@yield('title', 'Dashboard')</span>
                    </div>
                </div>

                <div class="flex items-center gap-3 md:gap-6">
                  <!-- Search Bar Terintegrasi -->
                    <form action="{{ route('search.global') }}" method="GET" class="relative hidden sm:block w-48 md:w-64">
                        <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                            <i class="fas fa-search text-xs"></i>
                        </button>
                        <input 
                            type="text" 
                            name="query" 
                            placeholder="Cari produk atau invoice..." 
                            value="{{ request('query') }}"
                            class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                        >
                    </form>

                    <div class="relative">
                        <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-100 relative" data-bs-toggle="dropdown">
                            <i class="far fa-bell"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow-xl rounded-2xl mt-2 w-80 overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                <h6 class="text-sm font-bold text-slate-800 m-0">Notifikasi</h6>
                                <span class="text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-bold">
                                    {{ auth()->user()->unreadNotifications->count() }} Baru
                                </span>
                            </div>
    
                            <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-4 py-3 hover:bg-slate-50 transition-colors no-underline border-b border-slate-50">
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0 w-8 h-8 bg-amber-50 text-amber-500 rounded-lg flex items-center justify-center">
                                                <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }} text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 mb-0.5">{{ $notification->data['title'] }}</p>
                                                <p class="text-[11px] text-slate-500 leading-tight">{{ $notification->data['pesan'] }}</p>
                                                <span class="text-[10px] text-slate-400 mt-2 block italic">
                                                    <i class="far fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-10 text-center">
                                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="far fa-bell-slash text-slate-300 text-xl"></i>
                                        </div>
                                        <p class="text-xs text-slate-400">Belum ada notifikasi baru</p>
                                    </div>
                                @endforelse
                            </div>

                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <a href="#" class="block py-2 text-center text-[11px] font-bold text-blue-600 bg-slate-50 hover:bg-blue-100 transition-colors no-underline uppercase tracking-wider">
                                    Tandai Semua Dibaca
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="flex items-center gap-3 no-underline" data-bs-toggle="dropdown">
                            <div class="text-right hidden lg:block">
                                <p class="text-sm font-semibold text-slate-800 leading-none mb-1">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter leading-none">{{ Auth::user()->role }}</p>
                            </div>
                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-md shadow-blue-100">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2 border-0 shadow-xl rounded-2xl mt-2 w-52">
                            <li><hr class="dropdown-divider border-slate-100"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-xl py-2 text-red-500 text-sm w-full text-left">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="p-4 md:p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl flex items-center justify-between shadow-sm animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle"></i>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

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

        // Close mobile sidebar on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) closeSidebar();
        });
    </script>
    
    @stack('scripts')
</body>
</html>