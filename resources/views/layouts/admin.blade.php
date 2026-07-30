<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'POS Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>


<body class="min-h-screen bg-slate-100 text-slate-800" x-data="{ sidebarOpen: false }" x-cloak>
    <div class="flex min-h-screen">
        <!-- Overlay untuk mobile/tablet saat sidebar terbuka -->
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-black/50 lg:hidden" style="display: none;"></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-40 w-72 shrink-0 transform overflow-y-auto bg-slate-900 p-6 text-white transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">POS Grosir</h2>
                    <p class="text-sm text-slate-400">Panel Admin</p>
                </div>
                <button @click="sidebarOpen = false" class="rounded-lg p-2 hover:bg-slate-800 lg:hidden" aria-label="Tutup menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18" /><path d="M6 6l12 12" /></svg>
                </button>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('dashboard') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12l9-9 9 9" /><path d="M5 10v10h14V10" /><path d="M9 20v-6h6v6" /></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('products') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('products*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5 9 5 9-5z" /><path d="M3 8v8l9 5 9-5V8" /><path d="M12 13v8" /></svg>
                    <span>Produk</span>
                </a>
                <a href="{{ route('categories') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('categories*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="8" rx="1.5" /><rect x="13" y="3" width="8" height="8" rx="1.5" /><rect x="3" y="13" width="8" height="8" rx="1.5" /><rect x="13" y="13" width="8" height="8" rx="1.5" /></svg>
                    <span>Kategori</span>
                </a>
                <a href="{{ route('suppliers') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('suppliers*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h2l2.4 12.4a2 2 0 0 0 2 1.6h8.2a2 2 0 0 0 2-1.6L21 8H6" /><circle cx="9" cy="20" r="1.4" /><circle cx="18" cy="20" r="1.4" /></svg>
                    <span>Supplier</span>
                </a>
                {{-- <a href="{{ route('customers') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('customers*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4" /><path d="M4 20c0-4 3.6-6 8-6s8 2 8 6" /></svg>
                    <span>Customer</span>
                </a> --}}
                <a href="{{ route('purchases') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('purchases*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h2l1 5m0 0h13l-1.5 8h-11L6 8z" /><circle cx="9.5" cy="19.5" r="1.4" /><circle cx="17" cy="19.5" r="1.4" /></svg>
                    <span>Pembelian</span>
                </a>
                <a href="{{ route('sales') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('sales') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="14" rx="2" /><path d="M8 6V4h8v2" /><path d="M3 11h18" /></svg>
                    <span>Penjualan</span>
                </a>
                <a href="{{ route('sales.history') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('sales.history') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8" /><path d="M3 4v4h4" /><path d="M12 7v5l3 3" /></svg>
                    <span>Riwayat Transaksi</span>
                </a>
                <a href="{{ route('sale-returns') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('sale-returns*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 14l-4-4 4-4" /><path d="M5 10h11a4 4 0 0 1 0 8h-1" /></svg>
                    <span>Retur Penjualan</span>
                </a>
                <a href="{{ route('purchase-returns') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('purchase-returns*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l4 4-4 4" /><path d="M19 10H8a4 4 0 0 0 0 8h1" /></svg>
                    <span>Retur Pembelian</span>
                </a>
                <a href="{{ route('stock') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('stock*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="12" rx="1.5" /><path d="M3 8l2.5-4h13L21 8" /><path d="M9 12h6" /></svg>
                    <span>Stok</span>
                </a>
                <a href="{{ route('stock-opnames') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('stock-opnames*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4" /><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" /></svg>
                    <span>Stok Opname</span>
                </a>
                <a href="{{ route('discounts') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('discounts*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12l-8-8H5a1 1 0 0 0-1 1v7l8 8a2 2 0 0 0 2.8 0l5.2-5.2a2 2 0 0 0 0-2.8z" /><circle cx="8.5" cy="8.5" r="1.4" /></svg>
                    <span>Diskon</span>
                </a>
                <a href="{{ route('reports.sales') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('reports*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4v16h16" /><path d="M8 16v-4" /><path d="M12 16V8" /><path d="M16 16v-7" /></svg>
                    <span>Laporan</span>
                </a>
                <a href="{{ route('settings') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('settings*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1.04 1.56V21a2 2 0 1 1-4 0v-.09A1.7 1.7 0 0 0 9 19.4a1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.56-1.04H3a2 2 0 1 1 0-4h.09A1.7 1.7 0 0 0 4.6 9a1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1.04-1.56V3a2 2 0 1 1 4 0v.09A1.7 1.7 0 0 0 15 4.6a1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.14.6.63 1.04 1.56 1.04H21a2 2 0 1 1 0 4h-.09A1.7 1.7 0 0 0 19.4 15z" /></svg>
                    <span>Pengaturan</span>
                </a>
                <a href="{{ route('audit-logs') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('audit-logs*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><path d="M14 2v6h6" /><path d="M9 15h6" /><path d="M9 11h6" /></svg>
                    <span>Audit Log</span>
                </a>
            </nav>

            <div class="mt-10 rounded-lg border border-slate-700 bg-slate-800 p-4 text-sm">
                <p class="font-medium">Aktif sebagai</p>
                <p class="text-slate-400">{{ auth()->user()?->name ?? 'Admin' }}</p>
            </div>
        </aside>

        <main class="flex-1 min-w-0 p-4 sm:p-6">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="sidebarOpen = true" class="rounded-lg p-2 hover:bg-slate-200 lg:hidden" aria-label="Buka menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16" /><path d="M4 12h16" /><path d="M4 18h16" /></svg>
                    </button>
                    <div class="min-w-0">
                        <h1 class="truncate text-xl font-semibold sm:text-2xl">@yield('page-title', 'Dashboard')</h1>
                        <p class="hidden text-sm text-slate-600 sm:block">@yield('page-subtitle', 'Panel administrasi POS')</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button class="rounded bg-slate-800 px-3 py-2 text-sm text-white sm:px-4">Logout</button>
                </form>
            </div>

            @yield('content')
        </main>
    </div>
</body>

</html>
