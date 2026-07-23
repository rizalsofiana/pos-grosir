@extends('layouts.pos')

@section('title', 'Kasir - Penjualan')
@section('page-title', 'Kasir Penjualan')
@section('page-subtitle', 'Transaksi penjualan ke customer')

@section('content')
    <div x-data="saleForm()" x-init="init()" class="flex h-full min-h-0">
        {{-- Product picker --}}
        <section class="flex min-h-0 w-full flex-col border-r bg-slate-50 lg:w-2/3">
            <div class="flex shrink-0 items-center gap-3 border-b bg-white p-3">
                <input type="text" x-model="search" placeholder="Cari produk / SKU..."
                    class="w-full rounded-lg border px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                <button type="button" @click="showHistory = true"
                    class="shrink-0 rounded-lg border px-3 py-2 text-sm text-slate-600 hover:bg-slate-100">
                    Riwayat
                </button>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto p-3">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <button type="button" @click="addItem(product)" :disabled="product.stock <= 0"
                            class="flex flex-col rounded-xl border bg-white p-3 text-left shadow-sm transition hover:border-blue-400 hover:shadow disabled:cursor-not-allowed disabled:opacity-40">
                            <span class="mb-1 line-clamp-2 text-sm font-medium" x-text="product.name"></span>
                            <span class="text-xs text-slate-400" x-text="product.sku"></span>
                            <span class="mt-2 text-sm font-semibold text-blue-600"
                                x-text="'Rp ' + product.selling_price.toLocaleString('id-ID')"></span>
                            <span class="mt-1 text-xs text-slate-500" x-text="'Stok: ' + product.stock"></span>
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

                <div class="shrink-0 space-y-2 border-b p-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">Customer (opsional)</label>
                        <select name="customer_id" class="w-full rounded-lg border px-3 py-2 text-sm">
                            <option value="">Umum / Tanpa data</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">Metode Pembayaran</label>
                        <select name="payment_method" class="w-full rounded-lg border px-3 py-2 text-sm" required>
                            <option value="cash">Cash</option>
                            <option value="cashless">Cashless (Midtrans)</option>
                        </select>
                    </div>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto p-3">
                    <template x-if="items.length === 0">
                        <p class="py-10 text-center text-sm text-slate-400">Belum ada item. Pilih produk di sebelah
                            kiri.</p>
                    </template>
                    <template x-for="(item, index) in items" :key="item.product_id">
                        <div class="mb-2 rounded-lg border p-2">
                            <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                            <input type="hidden" :name="'items[' + index + '][price]'" :value="item.price">
                            <input type="hidden" :name="'items[' + index + '][quantity]'" :value="item.quantity">
                            <div class="mb-1 flex items-start justify-between gap-2">
                                <span class="text-sm font-medium" x-text="item.name"></span>
                                <button type="button" @click="removeItem(index)"
                                    class="shrink-0 text-slate-400 hover:text-red-600">&times;</button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="decrement(item)"
                                        class="h-6 w-6 rounded bg-slate-100 text-sm hover:bg-slate-200">-</button>
                                    <input type="number" min="1" :max="item.stock"
                                        x-model.number="item.quantity" class="w-12 rounded border text-center text-sm">
                                    <button type="button" @click="increment(item)"
                                        class="h-6 w-6 rounded bg-slate-100 text-sm hover:bg-slate-200">+</button>
                                </div>
                                <span class="text-sm font-semibold"
                                    x-text="'Rp ' + (item.quantity * item.price).toLocaleString('id-ID')"></span>
                            </div>
                            <template x-if="lineDiscount(item) > 0">
                                <div class="mt-1 flex items-center justify-between text-xs text-green-600">
                                    <span>Diskon otomatis</span>
                                    <span x-text="'- Rp ' + lineDiscount(item).toLocaleString('id-ID')"></span>
                                </div>
                            </template>
                        </div>
                    </template>


                </div>

                <div class="shrink-0 space-y-2 border-t bg-slate-50 p-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">Sub Total</span>
                        <span x-text="'Rp ' + subTotal.toLocaleString('id-ID')"></span>
                    </div>
                    <template x-if="totalDiscount > 0">
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Diskon</span>
                            <span x-text="'- Rp ' + totalDiscount.toLocaleString('id-ID')"></span>
                        </div>
                    </template>
                    <div class="flex justify-between text-lg font-semibold">
                        <span>Total</span>
                        <span x-text="'Rp ' + grandTotal.toLocaleString('id-ID')"></span>
                    </div>

                    <button type="submit" :disabled="items.length === 0"
                        class="w-full rounded-lg bg-blue-600 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                        Proses Pembayaran
                    </button>
                </div>
            </form>
        </section>

        {{-- History modal --}}
        <div x-show="showHistory" x-cloak class="fixed inset-0 z-20 flex items-center justify-center bg-black/40 p-4">
            <div @click.outside="showHistory = false"
                class="flex max-h-[85vh] w-full max-w-3xl flex-col rounded-xl bg-white shadow-xl">
                <div class="flex shrink-0 items-center justify-between border-b p-4">
                    <h2 class="font-semibold">Riwayat Penjualan</h2>
                    <button type="button" @click="showHistory = false"
                        class="text-slate-400 hover:text-slate-800">&times;</button>
                </div>
                <div class="overflow-y-auto p-4">
                    @if (session('success'))
                        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}
                        </div>
                    @endif
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="py-2">Invoice</th>
                                <th class="py-2">Tanggal</th>
                                <th class="py-2">Customer</th>
                                <th class="py-2">Total</th>
                                <th class="py-2">Bayar</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $sale)
                                <tr class="border-b">
                                    <td class="py-2">{{ $sale->invoice_number }}</td>
                                    <td class="py-2">{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                                    <td class="py-2">{{ $sale->customer?->name ?? '-' }}</td>
                                    <td class="py-2">Rp {{ number_format($sale->grand_amount, 0, ',', '.') }}</td>
                                    <td class="py-2 capitalize">{{ $sale->payment_method }}</td>
                                    <td class="py-2">
                                        <a href="{{ route('sales.receipt', $sale) }}" target="_blank"
                                            class="text-blue-600 hover:underline">Struk</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-center text-slate-500">Belum ada transaksi
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
                    if (this.items.length === 0) {
                        event.preventDefault();
                    }
                },
            };
        }
    </script>
@endsection
