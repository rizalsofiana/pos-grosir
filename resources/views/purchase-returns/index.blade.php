@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Retur Pembelian')
@section('page-title', 'Retur Pembelian')
@section('page-subtitle', 'Proses pengembalian barang ke supplier')

@section('content')
    <div x-data="{ showSuccess: {{ session('success') ? 'true' : 'false' }} }">
        <div x-show="showSuccess" x-cloak x-transition
            class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12x" cy="12" r="9" />
                <path d="M8.5 12.5l2.5 2.5 4.5-5" />
            </svg>
            <span class="flex-1">{{ session('success') }}</span>
            <button type="button" @click="showSuccess = false" class="shrink-0 text-emerald-500 hover:text-emerald-700">
                &times;
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.1fr_1fr] xl:grid-cols-[1.3fr_1fr]">
        {{-- Riwayat Retur Pembelian --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm flex flex-col">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800">Riwayat Retur Pembelian</h2>
                    <p class="text-xs text-slate-400">{{ $purchaseReturns->total() }} retur tercatat</p>
                </div>
                <div class="flex items-center justify-between sm:justify-end gap-3">
                    <form method="GET" action="{{ route('purchase-returns') }}"
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
                            <path d="M15 6l4 4-4 4" />
                            <path d="M19 10H8a4 4 0 0 0 0 8h1" />
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Desktop / tablet view --}}
            <div class="hidden md:block max-h-160 overflow-y-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-slate-50/90 backdrop-blur z-10">
                        <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">No. Pembelian</th>
                            <th class="px-5 py-3">Supplier</th>
                            <th class="px-5 py-3">Kasir</th>
                            <th class="px-5 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($purchaseReturns as $purchaseReturn)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-3 text-slate-600">
                                    {{ \Illuminate\Support\Carbon::parse($purchaseReturn->return_date)->format('d/m/Y') }}
                                    <span
                                        class="block text-xs text-slate-400">{{ \Illuminate\Support\Carbon::parse($purchaseReturn->return_date)->format('H:i') }}</span>
                                </td>
                                <td class="px-5 py-3 font-medium text-slate-700">
                                    {{ $purchaseReturn->purchase?->invoice_number ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ $purchaseReturn->supplier?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $purchaseReturn->user?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-right font-semibold text-slate-800">
                                    Rp {{ number_format($purchaseReturn->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M15 6l4 4-4 4" />
                                            <path d="M19 10H8a4 4 0 0 0 0 8h1" />
                                        </svg>
                                        <p class="text-sm">Belum ada retur pembelian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile card list view --}}
            <div class="block md:hidden max-h-160 overflow-y-auto divide-y divide-slate-100">
                @forelse ($purchaseReturns as $purchaseReturn)
                    <div class="p-4 transition-colors hover:bg-slate-50">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <p class="text-xs text-slate-400">
                                    {{ \Illuminate\Support\Carbon::parse($purchaseReturn->return_date)->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $purchaseReturn->purchase?->invoice_number ?? '-' }}
                                </p>
                            </div>
                            <span class="text-sm font-semibold text-slate-800">
                                Rp {{ number_format($purchaseReturn->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-y-1 text-xs text-slate-500">
                            <span>Supplier</span>
                            <span class="text-right text-slate-700 font-medium">{{ $purchaseReturn->supplier?->name ?? '-' }}</span>
                            <span>Kasir</span>
                            <span class="text-right text-slate-700 font-medium">{{ $purchaseReturn->user?->name ?? '-' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center gap-2 px-5 py-14 text-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M15 6l4 4-4 4" />
                            <path d="M19 10H8a4 4 0 0 0 0 8h1" />
                        </svg>
                        <p class="text-sm">Belum ada retur pembelian.</p>
                    </div>
                @endforelse
            </div>

            <div class="flex flex-col items-center gap-3 border-t border-slate-100 px-5 py-3 text-sm text-slate-500 sm:flex-row sm:justify-between mt-auto">
                <span class="text-center sm:text-left">
                    Menampilkan {{ $purchaseReturns->firstItem() ?? 0 }}-{{ $purchaseReturns->lastItem() ?? 0 }}
                    dari {{ $purchaseReturns->total() }} data
                </span>
                <div>
                    {{ $purchaseReturns->onEachSide(1)->links() }}
                </div>
            </div>
        </div>

        {{-- Form Retur Pembelian --}}
        <div class="h-fit rounded-2xl border border-slate-200 bg-white shadow-sm" x-data="purchaseReturnForm()">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-800">Buat Retur Pembelian</h2>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                </span>
            </div>

            <form method="POST" action="{{ route('purchase-returns.store') }}" @submit="onSubmit"
                class="space-y-4 px-5 py-5">
                @csrf

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Supplier</label>
                        <select name="supplier_id" x-model="supplierId" required
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">Pilih Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Tanggal Retur</label>
                        <input type="datetime-local" name="return_date"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            :value="now" required>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Alasan</label>
                    <input type="text" name="reason" placeholder="Alasan retur ke supplier"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="block text-xs font-medium text-slate-500">Item yang Diretur</label>
                        <button type="button" @click="addItem()"
                            class="text-xs font-medium text-blue-600 hover:text-blue-700">+ Tambah Item</button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="item.key">
                            <div class="flex flex-col gap-2 rounded-xl border border-slate-200 bg-slate-50/60 p-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        <label class="mb-1 block text-[10px] font-medium text-slate-400">Pilih Produk</label>
                                        <select x-model="item.product_id" @change="onProductChange(item)"
                                            :name="'items[' + index + '][product_id]'" required
                                            class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none">
                                            <option value="">Pilih Produk</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->purchase_price ?? $product->price }}">
                                                    {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" @click="removeItem(index)"
                                        class="mt-4 rounded-lg px-2 py-1.5 text-sm text-red-500 hover:bg-red-50 shrink-0">Hapus</button>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-1">
                                    <div>
                                        <label class="mb-1 block text-[10px] font-medium text-slate-400">Qty</label>
                                        <input type="number" min="1" x-model.number="item.quantity"
                                            :name="'items[' + index + '][quantity]'" placeholder="Qty" required
                                            class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-center text-sm focus:border-blue-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[10px] font-medium text-slate-400">Harga</label>
                                        <input type="number" min="0" step="0.01" x-model.number="item.price"
                                            :name="'items[' + index + '][price]'" placeholder="Harga" required
                                            class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-right text-sm focus:border-blue-500 focus:outline-none">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Catatan tambahan (opsional)"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
                </div>

                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                    <span class="text-sm font-medium text-slate-600">Estimasi Total Retur</span>
                    <span class="text-lg font-semibold text-slate-800"
                        x-text="'Rp ' + estimatedTotal.toLocaleString('id-ID')"></span>
                </div>

                <button type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    Simpan Retur Pembelian
                </button>
            </form>
        </div>
    </div>

    <script>
        function purchaseReturnForm() {
            return {
                supplierId: '',
                now: '',
                items: [],
                init() {
                    const d = new Date();
                    d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                    this.now = d.toISOString().slice(0, 16);
                    this.addItem();
                },
                addItem() {
                    this.items.push({
                        key: Date.now() + Math.random(),
                        product_id: '',
                        quantity: 1,
                        price: 0,
                    });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                onProductChange(item) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    const price = option?.dataset?.price;
                    if (price) {
                        item.price = parseFloat(price);
                    }
                },
                get estimatedTotal() {
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
