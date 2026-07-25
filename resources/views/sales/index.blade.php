@extends('layouts.pos')

@section('title', 'Kasir - Penjualan')
@section('page-title', 'Kasir Penjualan')
@section('page-subtitle', 'Transaksi penjualan ke customer')

@section('content')
    <div x-data="saleForm()" x-init="init()" class="flex h-full min-h-0">
        {{-- Product picker --}}
        <section class="flex min-h-0 w-full flex-col border-r border-slate-200 bg-slate-50 lg:w-2/3">
            <div class="flex shrink-0 items-center gap-3 border-b border-slate-200 bg-white px-4 py-3">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <path d="M21 21l-3.5-3.5" />
                    </svg>
                    <input type="text" x-model="search" placeholder="Cari produk / SKU..."
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <button type="button" @click="showHistory = true"
                    class="flex shrink-0 items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8" />
                        <path d="M3 4v4h4" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                    Riwayat
                </button>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <button type="button" @click="addItem(product)" :disabled="product.stock <= 0"
                            class="flex flex-col rounded-xl border border-slate-200 bg-white p-3.5 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-blue-400 hover:shadow-md disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:translate-y-0">
                            <span class="mb-1 line-clamp-2 text-sm font-medium text-slate-800" x-text="product.name"></span>
                            <span class="text-xs text-slate-400" x-text="product.sku"></span>
                            <span class="mt-2 text-sm font-semibold text-blue-600"
                                x-text="'Rp ' + product.selling_price.toLocaleString('id-ID')"></span>
                            <span class="mt-1 inline-flex w-fit items-center rounded-full px-2 py-0.5 text-xs"
                                :class="product.stock > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500'"
                                x-text="'Stok: ' + product.stock"></span>
                        </button>
                    </template>
                    <template x-if="filteredProducts.length === 0">
                        <p class="col-span-full py-10 text-center text-sm text-slate-400">Produk tidak ditemukan.</p>
                    </template>
                </div>
            </div>
        </section>


        {{-- Cart / checkout --}}
        <section class="flex min-h-0 w-full flex-col bg-white lg:w-1/3">
            <form method="POST" action="{{ route('sales.store') }}" @submit="onSubmit"
                class="flex min-h-0 flex-1 flex-col">
                @csrf
                <input type="hidden" name="sale_date" :value="now">

                <div class="flex shrink-0 items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h2 class="text-sm font-semibold text-slate-800">Keranjang</h2>
                    <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-600"
                        x-text="items.length + ' item'"></span>
                </div>

                <div class="shrink-0 space-y-3 border-b border-slate-200 p-4">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Customer (opsional)</label>
                        <select name="customer_id"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">Umum / Tanpa data</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Metode Pembayaran</label>
                        <select name="payment_method"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            required>
                            <option value="cash">Cash</option>
                            <option value="cashless">Cashless (Midtrans)</option>
                        </select>
                    </div>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto p-4">
                    <template x-if="items.length === 0">
                        <div class="flex flex-col items-center gap-2 py-14 text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1" />
                                <circle cx="20" cy="21" r="1" />
                                <path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6" />
                            </svg>
                            <p class="text-sm text-slate-400">Belum ada item. Pilih produk di sebelah kiri.</p>
                        </div>
                    </template>
                    <template x-for="(item, index) in items" :key="item.product_id">
                        <div class="mb-2 rounded-xl border border-slate-200 p-3 transition hover:border-slate-300">
                            <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                            <input type="hidden" :name="'items[' + index + '][price]'" :value="item.price">
                            <input type="hidden" :name="'items[' + index + '][quantity]'" :value="item.quantity">
                            <div class="mb-2 flex items-start justify-between gap-2">
                                <span class="text-sm font-medium text-slate-800" x-text="item.name"></span>
                                <button type="button" @click="removeItem(index)"
                                    class="shrink-0 rounded p-0.5 text-slate-400 transition hover:bg-red-50 hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M18 6L6 18M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <button type="button" @click="decrement(item)"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-sm text-slate-600 transition hover:bg-slate-200">-</button>
                                    <input type="number" min="1" :max="item.stock"
                                        x-model.number="item.quantity"
                                        class="w-12 rounded-lg border border-slate-200 text-center text-sm">
                                    <button type="button" @click="increment(item)"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-sm text-slate-600 transition hover:bg-slate-200">+</button>
                                </div>
                                <span class="text-sm font-semibold text-slate-800"
                                    x-text="'Rp ' + (item.quantity * item.price).toLocaleString('id-ID')"></span>
                            </div>
                            <template x-if="lineDiscount(item) > 0">
                                <div
                                    class="mt-2 flex items-center justify-between rounded-lg bg-emerald-50 px-2 py-1 text-xs text-emerald-600">
                                    <span>Diskon otomatis</span>
                                    <span x-text="'- Rp ' + lineDiscount(item).toLocaleString('id-ID')"></span>
                                </div>
                            </template>
                        </div>
                    </template>


                </div>

                <div class="shrink-0 space-y-2 border-t border-slate-200 bg-slate-50 p-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Sub Total</span>
                        <span class="text-slate-700" x-text="'Rp ' + subTotal.toLocaleString('id-ID')"></span>
                    </div>
                    <template x-if="totalDiscount > 0">
                        <div class="flex justify-between text-sm text-emerald-600">
                            <span>Diskon</span>
                            <span x-text="'- Rp ' + totalDiscount.toLocaleString('id-ID')"></span>
                        </div>
                    </template>
                    <div
                        class="flex justify-between border-t border-dashed border-slate-300 pt-2 text-lg font-semibold text-slate-800">
                        <span>Total</span>
                        <span x-text="'Rp ' + grandTotal.toLocaleString('id-ID')"></span>
                    </div>

                    <button type="submit" :disabled="items.length === 0"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" />
                            <path d="M1 10h22" />
                        </svg>
                        Proses Pembayaran
                    </button>
                </div>
            </form>
        </section>


        {{-- History modal --}}
        <div x-show="showHistory" x-cloak x-transition.opacity
            class="fixed inset-0 z-20 flex items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm">
            <div @click.outside="showHistory = false" x-show="showHistory" x-transition
                class="flex max-h-[85vh] w-full max-w-3xl flex-col rounded-2xl bg-white shadow-xl">
                <div class="flex shrink-0 items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-slate-800">Riwayat Penjualan Hari Ini</h2>
                        <p class="text-xs text-slate-400">{{ $sales->count() }} transaksi tercatat</p>
                    </div>
                    <a href="{{ route('sales.history') }}"
                        class="mr-3 text-xs font-medium text-blue-600 hover:underline">Lihat riwayat lengkap</a>
                    <button type="button" @click="showHistory = false"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="overflow-y-auto p-4">
                    @if (session('success'))
                        <div
                            class="mb-4 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M8.5 12.5l2.5 2.5 4.5-5" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr
                                class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                                <th class="py-2 px-2">Invoice</th>
                                <th class="py-2 px-2">Tanggal</th>
                                <th class="py-2 px-2">Customer</th>
                                <th class="py-2 px-2 text-right">Total</th>
                                <th class="py-2 px-2">Bayar</th>
                                <th class="py-2 px-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($sales as $sale)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="py-2.5 px-2 font-medium text-slate-700">{{ $sale->invoice_number }}</td>
                                    <td class="py-2.5 px-2 text-slate-500">{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                                    <td class="py-2.5 px-2 text-slate-500">{{ $sale->customer?->name ?? '-' }}</td>
                                    <td class="py-2.5 px-2 text-right font-semibold text-slate-800">Rp
                                        {{ number_format($sale->grand_amount, 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-2">
                                        <span
                                            class="rounded-full px-2 py-0.5 text-xs capitalize {{ $sale->payment_method === 'cash' ? 'bg-slate-100 text-slate-600' : 'bg-blue-50 text-blue-600' }}">
                                            {{ $sale->payment_method }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-2">
                                        <a href="{{ route('sales.receipt', $sale) }}" target="_blank"
                                            class="font-medium text-blue-600 hover:underline">Struk</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-slate-400">Belum ada transaksi
                                        penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <?php
    $mappedProducts = $products
        ->map(
            fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'selling_price' => (float) $p->selling_price,
                'stock' => $p->stock,
                'category_id' => $p->category_id,
            ],
        )
        ->values();
    
    $mappedRules = $discountRules
        ->map(
            fn($r) => [
                'scope' => $r->scope,
                'product_id' => $r->product_id,
                'category_id' => $r->category_id,
                'min_qty' => $r->min_qty,
                'discount_type' => $r->discount_type,
                'discount_value' => (float) $r->discount_value,
            ],
        )
        ->values();
    ?>

    <script>
        function saleForm() {
            return {
                allProducts: @json($mappedProducts),
                discountRules: @json($mappedRules),
                search: '',
                items: [],
                showHistory: false,
                now: '',

                init() {
                    const d = new Date();
                    d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                    this.now = d.toISOString().slice(0, 16);
                    @if (session('success'))
                        this.showHistory = true;
                    @endif
                },
                get filteredProducts() {
                    const q = this.search.trim().toLowerCase();
                    if (!q) return this.allProducts;
                    return this.allProducts.filter(p =>
                        p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q));
                },
                addItem(product) {
                    if (product.stock <= 0) return;
                    const existing = this.items.find(i => i.product_id === product.id);
                    if (existing) {
                        if (existing.quantity < product.stock) existing.quantity++;
                        return;
                    }
                    this.items.push({
                        product_id: product.id,
                        name: product.name,
                        price: product.selling_price,
                        stock: product.stock,
                        category_id: product.category_id,
                        quantity: 1,
                    });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                increment(item) {
                    if (item.quantity < item.stock) item.quantity++;
                },
                decrement(item) {
                    if (item.quantity > 1) item.quantity--;
                },
                get subTotal() {
                    return this.items.reduce((sum, item) => sum + (item.quantity * item.price || 0), 0);
                },
                lineDiscount(item) {
                    const applicable = this.discountRules.filter(r =>
                        (r.scope === 'product' && r.product_id === item.product_id) ||
                        (r.scope === 'category' && r.category_id === item.category_id));

                    let best = 0;
                    applicable.forEach(rule => {
                        if (item.quantity < rule.min_qty) return;
                        const lineTotal = item.quantity * item.price;
                        let discount = rule.discount_type === 'percentage' ?
                            lineTotal * (rule.discount_value / 100) :
                            Math.min(rule.discount_value * item.quantity, lineTotal);
                        if (discount > best) best = discount;
                    });
                    return Math.round(best);
                },
                get totalDiscount() {
                    return this.items.reduce((sum, item) => sum + this.lineDiscount(item), 0);
                },
                get grandTotal() {
                    return this.subTotal - this.totalDiscount;
                },

                onSubmit(event) {
                    event.preventDefault();
                    if (this.items.length === 0) return;

                    const form = event.target;
                    const formData = new FormData(form);
                    const isCashless = formData.get('payment_method') === 'cashless';

                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                            },
                            body: formData,
                        })
                        .then(async (res) => {
                            if (!res.ok) {
                                const err = await res.json().catch(() => null);
                                throw new Error(err?.message || 'Transaksi gagal diproses.');
                            }
                            return res.json();
                        })
                        .then((data) => {
                            if (isCashless && data.snap_token) {
                                window.snap.pay(data.snap_token, {
                                    onSuccess: () => window.location.href = "{{ route('sales') }}",
                                    onPending: () => window.location.href = "{{ route('sales') }}",
                                    onError: () => alert('Pembayaran gagal, silakan coba lagi.'),
                                    onClose: () => window.location.href = "{{ route('sales') }}",
                                });
                            } else {
                                window.location.href = data.redirect || "{{ route('sales') }}";
                            }
                        })
                        .catch((err) => alert(err.message));
                },
            };
        }
    </script>

    @push('scripts')
        <script
            src="{{ $midtransIsProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ $midtransClientKey }}"></script>
    @endpush
@endsection
