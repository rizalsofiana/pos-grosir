<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'POS Kasir')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="h-screen overflow-hidden bg-slate-100 text-slate-800">
    <div class="flex h-screen flex-col">
        <header class="flex shrink-0 items-center justify-between border-b bg-white px-4 py-2.5 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}"
                    class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-800"
                    title="Kembali ke dashboard">
                    &larr;
                </a>
                <div>
                    <h1 class="text-base font-semibold leading-tight">@yield('page-title', 'POS Grosir')</h1>
                    <p class="text-xs text-slate-500 leading-tight">@yield('page-subtitle')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-600">{{ auth()->user()?->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg bg-slate-800 px-3 py-1.5 text-sm text-white hover:bg-slate-700">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="min-h-0 flex-1">
            @yield('content')
        </main>
    </div>
</body>

</html>
