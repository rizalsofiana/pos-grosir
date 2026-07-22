@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Pembelian')
@section('page-title', 'Transaksi Pembelian')
@section('page-subtitle', 'Pembelian barang dari supplier')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_1.1fr]">
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Riwayat Pembelian</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2">Tanggal</th>
                            <th class="py-2">Supplier</th>
                            <th class="py-2">Kasir</th>
                            <th class="py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                            <tr class="border-b">
                                <td class="py-2">{{ $purchase->purchase_date->format('d/m/Y H:i') }}</td>
                                <td class="py-2">{{ $purchase->supplier?->name ?? '-' }}</td>
                                <td class="py-2">{{ $purchase->user?->name ?? '-' }}</td>
                                <td class="py-2">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-slate-500">Belum ada transaksi pembelian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-xl bg-white p-4 shadow" x-data="purchaseForm()">
            <h2 class="mb-4 font-semibold">Tambah Pembelian</h2>
            <form method="POST" action="{{ route('purchases.store') }}" @submit="onSubmit" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm">Supplier</label>
                    <select name="supplier_id" class="w-full rounded border px-3 py-2" required>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm">Tanggal</label>
                    <input type="datetime-local" name="purchase_date" class="w-full rounded border px-3 py-2"
                        :value="now" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm">Item Pembelian</label>
                    <div class="space-y-2">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="grid grid-cols-12 items-center gap-2 rounded border p-2">
                                <select :name="'items[' + index + '][product_id]'" x-model.number="item.product_id"
                                    @change="onProductChange(item)" class="col-span-5 rounded border px-2 py-1.5 text-sm"
                                    required>
                                    <option value="">Pilih produk</option>
                                    <template x-for="product in allProducts" :key="product.id">
                                        <option :value="product.id" x-text="product.name + ' (' + product.sku + ')'">
                                        </option>
                                    </template>
                                </select>
                                <input type="number" min="1" :name="'items[' + index + '][quantity]'"
                                    x-model.number="item.quantity" placeholder="Qty"
                                    class="col-span-2 rounded border px-2 py-1.5 text-sm text-center" required>
                                <input type="number" step="0.01" min="0" :name="'items[' + index + '][price]'"
                                    x-model.number="item.price" placeholder="Harga"
                                    class="col-span-4 rounded border px-2 py-1.5 text-sm text-right" required>
                                <button type="button" @click="removeItem(index)"
                                    class="col-span-1 text-slate-400 hover:text-red-600">&times;</button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="addItem"
                        class="mt-2 rounded border border-dashed px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-50">
                        + Tambah Item
                    </button>
                </div>

                <div class="flex justify-between border-t pt-3 text-lg font-semibold">
                    <span>Total</span>
                    <span x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                </div>

                <button type="submit" :disabled="items.length === 0"
                    class="w-full rounded bg-blue-600 px-4 py-2 text-white disabled:cursor-not-allowed disabled:opacity-50">
                    Simpan Pembelian
                </button>
            </form>
        </div>
    </div>

    <?php
    $mappedProducts = $products->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'purchase_price' => $product->purchase_price,
        ];
    });
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
