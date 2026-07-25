@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Pembelian')
@section('page-title', 'Transaksi Pembelian')
@section('page-subtitle', 'Pembelian barang dari supplier')

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

    <div class="grid gap-6 lg:grid-cols-[1.1fr_1fr] xl:grid-cols-[1.3fr_1fr]">
        {{-- Riwayat Pembelian --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="font-semibold text-slate-800">Riwayat Pembelian</h2>
                    <p class="text-xs text-slate-400">{{ $purchases->count() }} transaksi tercatat</p>
                </div>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3h2l1 5m0 0h13l-1.5 8h-11L6 8z" />
                        <circle cx="9.5" cy="19.5" r="1.4" />
                        <circle cx="17" cy="19.5" r="1.4" />
                    </svg>
                </span>
            </div>

            <div class="max-h-160 overflow-y-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-slate-50/90 backdrop-blur">
                        <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Supplier</th>
                            <th class="px-5 py-3">Kasir</th>
                            <th class="px-5 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($purchases as $purchase)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-3 text-slate-600">
                                    {{ $purchase->purchase_date->format('d/m/Y') }}
                                    <span
                                        class="block text-xs text-slate-400">{{ $purchase->purchase_date->format('H:i') }}</span>
                                </td>
                                <td class="px-5 py-3 font-medium text-slate-700">{{ $purchase->supplier?->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-slate-500">{{ $purchase->user?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-right font-semibold text-slate-800">
                                    Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M3 3h2l1 5m0 0h13l-1.5 8h-11L6 8z" />
                                            <circle cx="9.5" cy="19.5" r="1.4" />
                                            <circle cx="17" cy="19.5" r="1.4" />
                                        </svg>
                                        <p class="text-sm">Belum ada transaksi pembelian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Tambah Pembelian --}}
        <div class="h-fit rounded-2xl border border-slate-200 bg-white shadow-sm" x-data="purchaseForm()"
            x-init="init()">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-800">Tambah Pembelian</h2>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                </span>
            </div>

            <form method="POST" action="{{ route('purchases.store') }}" @submit="onSubmit" class="space-y-4 px-5 py-5">
                @csrf
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Supplier</label>
                        <select name="supplier_id"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            required>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Tanggal</label>
                        <input type="datetime-local" name="purchase_date"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            :value="now" required>
                    </div>
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="block text-xs font-medium text-slate-500">Item Pembelian</label>
                        <span class="text-xs text-slate-400" x-text="items.length + ' item'"></span>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(item, index) in items" :key="index">
                            <div
                                class="grid grid-cols-12 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50/60 p-2.5">
                                <select :name="'items[' + index + '][product_id]'" x-model.number="item.product_id"
                                    @change="onProductChange(item)"
                                    class="col-span-12 rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none sm:col-span-5"
                                    required>
                                    <option value="">Pilih produk</option>
                                    <template x-for="product in allProducts" :key="product.id">
                                        <option :value="product.id" x-text="product.name + ' (' + product.sku + ')'">
                                        </option>
                                    </template>
                                </select>
                                <input type="number" min="1" :name="'items[' + index + '][quantity]'"
                                    x-model.number="item.quantity" placeholder="Qty"
                                    class="col-span-4 rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-center text-sm focus:border-blue-500 focus:outline-none sm:col-span-2"
                                    required>
                                <input type="number" step="0.01" min="0" :name="'items[' + index + '][price]'"
                                    x-model.number="item.price" placeholder="Harga"
                                    class="col-span-6 rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-right text-sm focus:border-blue-500 focus:outline-none sm:col-span-4"
                                    required>
                                <button type="button" @click="removeItem(index)"
                                    class="col-span-2 flex items-center justify-center rounded-lg py-1.5 text-slate-400 transition hover:bg-red-50 hover:text-red-500 sm:col-span-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M18 6L6 18M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="addItem()"
                        class="mt-2 flex w-full items-center justify-center gap-1.5 rounded-lg border border-dashed border-slate-300 py-2 text-sm text-slate-500 transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Tambah Item
                    </button>
                </div>

                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                    <span class="text-sm font-medium text-slate-600">Total</span>
                    <span class="text-lg font-semibold text-slate-800"
                        x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                </div>

                <button type="submit" :disabled="items.length === 0"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                    Simpan Pembelian
                </button>
            </form>
        </div>
    </div>

    <?php
    $mappedProducts = $products
        ->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'purchase_price' => $product->purchase_price,
            ];
        })
        ->values();
    ?>

    <script>
        function purchaseForm() {
            return {
                allProducts: @json($mappedProducts),
                items: [],
                now: '',
                init() {
                    const d = new Date();
                    d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                    this.now = d.toISOString().slice(0, 16);
                    this.addItem();
                },
                addItem() {
                    this.items.push({
                        product_id: '',
                        quantity: 1,
                        price: 0
                    });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                onProductChange(item) {
                    const product = this.allProducts.find(p => p.id === item.product_id);
                    if (product) item.price = product.purchase_price;
                },
                get total() {
                    return this.items.reduce((sum, item) => sum + ((item.quantity || 0) * (item.price || 0)), 0);
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
