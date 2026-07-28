@extends('layouts.admin')

@section('title', 'Stok Opname')
@section('page-title', 'Stok Opname')
@section('page-subtitle', 'Pencocokan stok sistem dengan stok fisik gudang')

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

    <div class="grid gap-6 lg:grid-cols-[1fr_1.2fr]">
        {{-- Riwayat Stok Opname --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm flex flex-col">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800">Riwayat Stok Opname</h2>
                    <p class="text-xs text-slate-400">{{ $stockOpnames->total() }} sesi tercatat</p>
                </div>
                <div class="flex items-center justify-between sm:justify-end gap-3">
                    <form method="GET" action="{{ route('stock-opnames') }}"
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
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="8" width="18" height="12" rx="1.5" />
                            <path d="M3 8l2.5-4h13L21 8" />
                            <path d="M9 12h6" />
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Desktop / tablet view --}}
            <div class="hidden md:block max-h-160 overflow-y-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-slate-50/90 backdrop-blur z-10">
                        <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Petugas</th>
                            <th class="px-5 py-3 text-right">Item</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($stockOpnames as $stockOpname)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-3 font-medium text-slate-700">{{ $stockOpname->code }}</td>
                                <td class="px-5 py-3 text-slate-600">
                                    {{ \Illuminate\Support\Carbon::parse($stockOpname->opname_date)->format('d/m/Y') }}
                                    <span
                                        class="block text-xs text-slate-400">{{ \Illuminate\Support\Carbon::parse($stockOpname->opname_date)->format('H:i') }}</span>
                                </td>
                                <td class="px-5 py-3 text-slate-500">{{ $stockOpname->user?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-right text-slate-600">
                                    {{ $stockOpname->stock_opname_details_count }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('stock-opnames.show', $stockOpname) }}"
                                        class="text-xs font-medium text-blue-600 hover:text-blue-700">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="8" width="18" height="12" rx="1.5" />
                                            <path d="M3 8l2.5-4h13L21 8" />
                                        </svg>
                                        <p class="text-sm">Belum ada sesi stok opname.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile card list view --}}
            <div class="block md:hidden max-h-160 overflow-y-auto divide-y divide-slate-100">
                @forelse ($stockOpnames as $stockOpname)
                    <div class="p-4 transition-colors hover:bg-slate-50">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <p class="text-xs text-slate-400">
                                    {{ \Illuminate\Support\Carbon::parse($stockOpname->opname_date)->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-sm font-semibold text-slate-800">{{ $stockOpname->code }}</p>
                            </div>
                            <a href="{{ route('stock-opnames.show', $stockOpname) }}"
                                class="text-xs font-medium text-blue-600 hover:text-blue-700">Detail &rarr;</a>
                        </div>
                        <div class="grid grid-cols-2 gap-y-1 text-xs text-slate-500">
                            <span>Petugas</span>
                            <span class="text-right text-slate-700 font-medium">{{ $stockOpname->user?->name ?? '-' }}</span>
                            <span>Total Item</span>
                            <span class="text-right text-slate-700 font-medium">{{ $stockOpname->stock_opname_details_count }} produk</span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center gap-2 px-5 py-14 text-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="8" width="18" height="12" rx="1.5" />
                            <path d="M3 8l2.5-4h13L21 8" />
                        </svg>
                        <p class="text-sm">Belum ada sesi stok opname.</p>
                    </div>
                @endforelse
            </div>

            <div class="flex flex-col items-center gap-3 border-t border-slate-100 px-5 py-3 text-sm text-slate-500 sm:flex-row sm:justify-between mt-auto">
                <span class="text-center sm:text-left">
                    Menampilkan {{ $stockOpnames->firstItem() ?? 0 }}-{{ $stockOpnames->lastItem() ?? 0 }}
                    dari {{ $stockOpnames->total() }} data
                </span>
                <div>
                    {{ $stockOpnames->onEachSide(1)->links() }}
                </div>
            </div>
        </div>

        {{-- Form Stok Opname --}}
        <div class="h-fit rounded-2xl border border-slate-200 bg-white shadow-sm" x-data="stockOpnameForm()">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-800">Buat Sesi Stok Opname</h2>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                </span>
            </div>

            <form method="POST" action="{{ route('stock-opnames.store') }}" @submit="onSubmit"
                class="space-y-4 px-5 py-5">
                @csrf

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Tanggal Opname</label>
                        <input type="datetime-local" name="opname_date"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            :value="now" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Cari Produk</label>
                        <input type="text" x-model="search" placeholder="Ketik nama produk..."
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="block text-xs font-medium text-slate-500">Stok Fisik Produk</label>
                        <span class="text-xs text-slate-400" x-text="checkedCount + ' produk diisi'"></span>
                    </div>
                    <div class="max-h-96 space-y-3 overflow-y-auto rounded-xl border border-slate-200 p-3">
                        <template x-for="product in filteredProducts" :key="product.id">
                            {{-- Container item --}}
                            <div class="flex flex-col gap-2 rounded-xl border border-slate-200 bg-slate-50/60 p-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-700" x-text="product.name"></p>
                                    <p class="text-xs text-slate-400">
                                        Stok Sistem: <span class="font-medium text-slate-600" x-text="product.stock"></span>
                                    </p>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-1">
                                    <div>
                                        <label class="mb-1 block text-[10px] font-medium text-slate-400">Stok Fisik</label>
                                        <input type="number" min="0" x-model.number="product.physical_stock"
                                            placeholder="Fisik"
                                            class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-center text-sm focus:border-blue-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[10px] font-medium text-slate-400">Catatan</label>
                                        <input type="text" x-model="product.note" placeholder="Catatan"
                                            class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Catatan Sesi</label>
                    <textarea name="notes" rows="2" placeholder="Catatan tambahan (opsional)"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
                </div>

                <template x-for="(product, index) in filledProducts" :key="product.id">
                    <div>
                        <input type="hidden" :name="'items[' + index + '][product_id]'" :value="product.id">
                        <input type="hidden" :name="'items[' + index + '][physical_stock]'"
                            :value="product.physical_stock">
                        <input type="hidden" :name="'items[' + index + '][note]'" :value="product.note">
                    </div>
                </template>

                <button type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    Simpan Stok Opname
                </button>
            </form>
        </div>
    </div>

    <?php
    $mappedProducts = $products
        ->map(
            fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'stock' => $p->stock,
                'physical_stock' => null,
                'note' => '',
            ],
        )
        ->values();
    ?>

    <script>
        function stockOpnameForm() {
            return {
                now: '',
                search: '',
                products: @json($mappedProducts),
                init() {
                    const d = new Date();
                    d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                    this.now = d.toISOString().slice(0, 16);
                },
                get filteredProducts() {
                    if (!this.search) return this.products;
                    const term = this.search.toLowerCase();
                    return this.products.filter(p => p.name.toLowerCase().includes(term));
                },
                get filledProducts() {
                    return this.products.filter(p => p.physical_stock !== null && p.physical_stock !== '');
                },
                get checkedCount() {
                    return this.filledProducts.length;
                },
                onSubmit(event) {
                    if (this.filledProducts.length === 0) {
                        event.preventDefault();
                        alert('Isi minimal satu stok fisik produk.');
                    }
                },
            };
        }
    </script>
@endsection
