@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok')
@section('page-subtitle', 'Pantau dan sesuaikan stok produk')

@section('content')
    <div x-data="{ showSuccess: {{ session('success') ? 'true' : 'false' }} }">
        <div x-show="showSuccess" x-cloak x-transition
            class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9" />
                <path d="M8.5 12.5l2.5 2.5 4.5-5" />
            </svg>
            <span class="flex-1">{{ session('success') }}</span>
            <button type="button" @click="showSuccess = false" class="shrink-0 text-emerald-500 hover:text-emerald-700">
                &times;
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid gap-6 @if (auth()->user()->isAdmin()) lg:grid-cols-[1.3fr_1fr] @endif">
        {{-- Daftar Stok --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm flex flex-col">
            <div
                class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800">Daftar Stok Produk</h2>
                    <p class="text-xs text-slate-400">{{ $products->total() }} produk terdaftar</p>
                </div>
                <div class="flex items-center justify-between sm:justify-end gap-3">
                    <form method="GET" action="{{ route('stock') }}"
                        class="flex items-center gap-2 text-sm text-slate-500">
                        <label for="per_page">Tampilkan</label>
                        <select id="per_page" name="per_page" onchange="this.form.submit()"
                            class="rounded-lg border border-slate-200 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none">
                            @foreach ([10, 25, 50, 100] as $option)
                                <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                                    {{ $option }}</option>
                            @endforeach
                        </select>
                    </form>
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 8h18M3 8l2-5h14l2 5M3 8v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V8" />
                            <path d="M9 12h6" />
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Desktop / tablet view --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">SKU</th>
                            <th class="px-5 py-3">Nama Produk</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3 text-right">Stok</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($products as $product)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-3 text-slate-500">{{ $product->sku }}</td>
                                <td class="px-5 py-3 font-medium text-slate-700">{{ $product->name }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $product->category?->name ?? '-' }}</td>
                                <td
                                    class="px-5 py-3 text-right font-semibold {{ $product->stock <= 0 ? 'text-red-600' : 'text-slate-800' }}">
                                    {{ $product->stock }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('stock.history', $product) }}"
                                        class="text-blue-600 hover:underline">Riwayat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M3 8h18M3 8l2-5h14l2 5M3 8v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V8" />
                                            <path d="M9 12h6" />
                                        </svg>
                                        <p class="text-sm">Belum ada produk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile card list view --}}
            <div class="block md:hidden divide-y divide-slate-100">
                @forelse ($products as $product)
                    <div class="p-4 transition-colors hover:bg-slate-50">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400">SKU: {{ $product->sku }}</p>
                            </div>
                            <span
                                class="text-sm font-semibold {{ $product->stock <= 0 ? 'text-red-600' : 'text-slate-800' }}">
                                {{ $product->stock }} Pcs
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>Kategori: <strong
                                    class="text-slate-700">{{ $product->category?->name ?? '-' }}</strong></span>
                            <a href="{{ route('stock.history', $product) }}"
                                class="text-blue-600 font-medium hover:underline">Liwayat Stok &rarr;</a>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center gap-2 px-5 py-14 text-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 8h18M3 8l2-5h14l2 5M3 8v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V8" />
                            <path d="M9 12h6" />
                        </svg>
                        <p class="text-sm">Belum ada produk.</p>
                    </div>
                @endforelse
            </div>

            <div
                class="flex flex-col items-center gap-3 border-t border-slate-100 px-5 py-3 text-sm text-slate-500 sm:flex-row sm:justify-between mt-auto">
                <span class="text-center sm:text-left">
                    Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                    dari {{ $products->total() }} data
                </span>
                <div>
                    {{ $products->onEachSide(1)->links() }}
                </div>
            </div>
        </div>

        {{-- Form Penyesuaian Stok --}}
        @if (auth()->user()->isAdmin())
            <div class="h-fit rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h2 class="font-semibold text-slate-800">Penyesuaian Stok Manual</h2>
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20V10M12 10l-4 4M12 10l4 4M4 4h16" />
                        </svg>
                    </span>
                </div>

                <form method="POST" action="{{ route('stock.adjust') }}" class="space-y-4 px-5 py-5">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Produk</label>
                        <select name="product_id"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            required>
                            <option value="">Pilih produk</option>
                            @foreach ($allProducts as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }}) -
                                    Stok: {{ $product->stock }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-500">Jenis</label>
                            <select name="type"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                required>
                                <option value="in">Tambah (Masuk)</option>
                                <option value="out">Kurangi (Keluar)</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-500">Jumlah</label>
                            <input type="number" name="quantity" min="1"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Alasan</label>
                        <select name="reason"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            required>
                            <option value="Koreksi Stok Fisik">Koreksi Stok Fisik</option>
                            <option value="Barang Rusak">Barang Rusak</option>
                            <option value="Barang Hilang">Barang Hilang</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Catatan (opsional)</label>
                        <textarea name="note" rows="2"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        Simpan Penyesuaian
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
