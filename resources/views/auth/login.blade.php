<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div
            class="grid w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-xl shadow-slate-200/60 md:grid-cols-2">

            <!-- Branding panel -->
            <div class="relative hidden flex-col justify-between bg-slate-900 p-10 text-white md:flex">
                <div>
                    <h2 class="text-2xl font-semibold">POS Grosir</h2>
                    <p class="mt-1 text-sm text-slate-400">Sistem Kasir &amp; Manajemen Toko</p>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border border-slate-700 bg-slate-800/60 p-4">
                        <p class="text-sm font-medium">Kelola penjualan lebih cepat</p>
                        <p class="mt-1 text-xs text-slate-400">Transaksi, stok, dan laporan dalam satu dashboard.</p>
                    </div>
                    <p class="text-xs text-slate-500">&copy; {{ date('Y') }} POS Grosir. All rights reserved.</p>
                </div>

                <div
                    class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-blue-600/20 blur-3xl">
                </div>
                <div
                    class="pointer-events-none absolute -bottom-20 -left-10 h-56 w-56 rounded-full bg-slate-700/40 blur-3xl">
                </div>
            </div>

            <!-- Form panel -->
            <div class="p-8 sm:p-10">
                <div class="mb-8 md:hidden">
                    <h2 class="text-xl font-semibold">POS Grosir</h2>
                    <p class="text-sm text-slate-500">Sistem Kasir &amp; Manajemen Toko</p>
                </div>

                <h1 class="text-2xl font-semibold text-slate-900">Selamat Datang</h1>
                <p class="mt-1 mb-6 text-sm text-slate-500">Masuk untuk melanjutkan ke akun Anda.</p>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5" x-data="{ showPassword: false }">
                    @csrf

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M4 4h16v16H4V4z" stroke="none" />
                                    <path d="M4 6l8 7 8-7" />
                                    <path d="M4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6z" />
                                </svg>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                autofocus placeholder="nama@email.com"
                                class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-900 placeholder-slate-400 outline-none transition-all duration-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="5" y="11" width="14" height="9" rx="2" />
                                    <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                                </svg>
                            </span>
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                placeholder="••••••••"
                                class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-10 text-sm text-slate-900 placeholder-slate-400 outline-none transition-all duration-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition-colors duration-300 hover:text-slate-600"
                                tabindex="-1">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a20.3 20.3 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 7 11 7a20.3 20.3 0 0 1-2.16 3.19" />
                                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24" />
                                    <path d="M1 1l22 22" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-slate-600">
                            <input type="checkbox" name="remember" value="1"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/30">
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-600/30 transition-all duration-300 hover:bg-blue-700 hover:shadow-md hover:shadow-blue-600/40 active:scale-[0.99]">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
