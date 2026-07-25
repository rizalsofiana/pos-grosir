<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'POS Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">
    <div class="flex min-h-screen">
        <aside class="w-72 shrink-0 bg-slate-900 p-6 text-white">
            <div class="mb-8">
                <h2 class="text-xl font-semibold">POS Grosir</h2>
                <p class="text-sm text-slate-400">Panel Kasir</p>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('dashboard') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12l9-9 9 9" /><path d="M5 10v10h14V10" /><path d="M9 20v-6h6v6" /></svg>
                    <span>Dashboard</span>
                </a>
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
                <a href="{{ route('stock') }}"

                    class="flex items-center gap-3 rounded-lg px-4 py-2 transition-colors duration-200 hover:bg-slate-800 {{ request()->routeIs('stock*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="12" rx="1.5" /><path d="M3 8l2.5-4h13L21 8" /><path d="M9 12h6" /></svg>
                    <span>Stok</span>
                </a>
            </nav>
            <div class="mt-10 rounded-lg border border-slate-700 bg-slate-800 p-4 text-sm">
                <p class="font-medium">Aktif sebagai</p>
                <p class="text-slate-400">{{ auth()->user()?->name ?? 'Admin' }}</p>
            </div>
        </aside>

        <main class="flex-1 p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-sm text-slate-600">@yield('page-subtitle', 'Panel administrasi POS')</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded bg-slate-800 px-4 py-2 text-white">Logout</button>
                </form>
            </div>

            @yield('content')
        </main>
    </div>
</body>

</html>
