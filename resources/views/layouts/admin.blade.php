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
                <p class="text-sm text-slate-400">Panel Admin</p>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="block rounded-lg px-4 py-2 hover:bg-slate-800 {{ request()->routeIs('dashboard') ? 'bg-slate-800' : '' }}">Dashboard</a>
                <a href="{{ route('products') }}"
                    class="block rounded-lg px-4 py-2 hover:bg-slate-800 {{ request()->routeIs('products*') ? 'bg-slate-800' : '' }}">Produk</a>
                <a href="{{ route('categories') }}"
                    class="block rounded-lg px-4 py-2 hover:bg-slate-800 {{ request()->routeIs('categories*') ? 'bg-slate-800' : '' }}">Kategori</a>
                <a href="{{ route('suppliers') }}"
                    class="block rounded-lg px-4 py-2 hover:bg-slate-800 {{ request()->routeIs('suppliers*') ? 'bg-slate-800' : '' }}">Supplier</a>
                <a href="{{ route('customers') }}"
                    class="block rounded-lg px-4 py-2 hover:bg-slate-800 {{ request()->routeIs('customers*') ? 'bg-slate-800' : '' }}">Customer</a>
                <a href="{{ route('purchases') }}"
                    class="block rounded-lg px-4 py-2 hover:bg-slate-800 {{ request()->routeIs('purchases*') ? 'bg-slate-800' : '' }}">Pembelian</a>
                <a href="{{ route('sales') }}"
                    class="block rounded-lg px-4 py-2 hover:bg-slate-800 {{ request()->routeIs('sales*') ? 'bg-slate-800' : '' }}">Penjualan</a>
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
