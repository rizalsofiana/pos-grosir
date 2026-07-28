@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Retur Penjualan')
@section('page-title', 'Retur Penjualan')
@section('page-subtitle', 'Proses pengembalian barang dari pelanggan')

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
        {{-- Riwayat Retur Penjualan --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm flex flex-col">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800">Riwayat Retur Penjualan</h2>
                    <p class="text-xs text-slate-400">{{ $saleReturns->total() }} retur tercatat</p>
                </div>
                <div class="flex items-center justify-between sm:justify-end gap-3">
                    <form method="GET" action="{{ route('sale-returns') }}"
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
                            <path d="M9 14l-4-4 4-4" />
                            <path d="M5 10h11a4 4 0 0 1 0 8h-1" />
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
                            <th class="px-5 py-3">Invoice</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Kasir</th>
                            <th class="px-5 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($saleReturns as $saleReturn)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-3 text-slate-600">
                                    {{ \Illuminate\Support\Carbon::parse($saleReturn->return_date)->format('d/m/Y') }}
                                    <span
                                        class="block text-xs text-slate-400">{{ \Illuminate\Support\Carbon::parse($saleReturn->return_date)->format('H:i') }}</span>
                                </td>
                                <td class="px-5 py-3 font-medium text-slate-700">
                                    {{ $saleReturn->sale?->invoice_number ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ $saleReturn->customer?->name ?? 'Umum' }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $saleReturn->user?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-right font-semibold text-slate-800">
                                    Rp {{ number_format($saleReturn->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M9 14l-4-4 4-4" />
                                            <path d="M5 10h11a4 4 0 0 1 0 8h-1" />
                                        </svg>
                                        <p class="text-sm">Belum ada retur penjualan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile card list view --}}
            <div class="block md:hidden max-h-160 overflow-y-auto divide-y divide-slate-100">
                @forelse ($saleReturns as $saleReturn)
                    <div class="p-4 transition-colors hover:bg-slate-50">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <p class="text-xs text-slate-400">
                                    {{ \Illuminate\Support\Carbon::parse($saleReturn->return_date)->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $saleReturn->sale?->invoice_number ?? '-' }}
                                </p>
                            </div>
                            <span class="text-sm font-semibold text-slate-800">
                                Rp {{ number_format($saleReturn->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-y-1 text-xs text-slate-500">
                            <span>Customer</span>
                            <span class="text-right text-slate-700 font-medium">{{ $saleReturn->customer?->name ?? 'Umum' }}</span>
                            <span>Kasir</span>
                            <span class="text-right text-slate-700 font-medium">{{ $saleReturn->user?->name ?? '-' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center gap-2 px-5 py-14 text-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M9 14l-4-4 4-4" />
                            <path d="M5 10h11a4 4 0 0 1 0 8h-1" />
                        </svg>
                        <p class="text-sm">Belum ada retur penjualan.</p>
                    </div>
                @endforelse
            </div>

            <div class="flex flex-col items-center gap-3 border-t border-slate-100 px-5 py-3 text-sm text-slate-500 sm:flex-row sm:justify-between mt-auto">
                <span class="text-center sm:text-left">
                    Menampilkan {{ $saleReturns->firstItem() ?? 0 }}-{{ $saleReturns->lastItem() ?? 0 }}
                    dari {{ $saleReturns->total() }} data
                </span>
                <div>
                    {{ $saleReturns->onEachSide(1)->links() }}
                </div>
            </div>
        </div>

        {{-- Form Retur Penjualan --}}
        <div class="h-fit rounded-2xl border border-slate-200 bg-white shadow-sm" x-data="saleReturnForm()">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-800">Buat Retur Penjualan</h2>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                </span>
            </div>

            <div class="space-y-4 px-5 py-5">
                {{-- Cari Invoice --}}
                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Nomor Invoice</label>
                    <div class="flex gap-2">
                        <input type="text" x-model="invoiceNumber" @keydown.enter.prevent="findSale()"
                            placeholder="Masukkan nomor invoice"
                            class="flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <button type="button" @click="findSale()" :disabled="loading"
                            class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900 disabled:opacity-50">
                            <span x-show="!loading">Cari</span>
                            <span x-show="loading">Mencari...</span>
                        </button>
                    </div>
                    <p x-show="errorMessage" x-text="errorMessage" class="mt-1 text-xs text-red-500"></p>
                </div>

                <template x-if="sale">
                    <form method="POST" action="{{ route('sale-returns.store') }}" @submit="onSubmit"
                        class="space-y-4 border-t border-slate-100 pt-4">
                        @csrf
                        <input type="hidden" name="sale_id" :value="sale?.id">

                        <div class="rounded-xl bg-slate-50 px-3 py-2 text-sm text-slate-600">
                            <p><span class="font-medium text-slate-700">Customer:</span> <span
                                    x-text="sale?.customer?.name ?? 'Umum'"></span></p>
                            <p><span class="font-medium text-slate-700">Tanggal Transaksi:</span> <span
                                    x-text="sale?.sale_date"></span></p>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-500">Tanggal Retur</label>
                                <input type="datetime-local" name="return_date"
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                    :value="now" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-500">Alasan</label>
                                <input type="text" name="reason" placeholder="Alasan retur"
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-medium text-slate-500">Item yang Diretur</label>
                            <div class="space-y-3">
                                <template x-for="(detail, index) in saleDetails" :key="detail.id">
                                    <div x-show="detail.returnable_quantity > 0"
                                        class="rounded-xl border border-slate-200 bg-slate-50/60 p-3 flex flex-col gap-2">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="flex items-start gap-2">
                                                <input type="checkbox" x-model="detail.selected"
                                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                <div>
                                                    <span class="text-sm font-medium text-slate-700 block"
                                                        x-text="detail.product?.name"></span>
                                                    <span class="text-xs text-slate-400 block"
                                                        x-text="'Sisa bisa diretur: ' + detail.returnable_quantity"></span>
                                                </div>
                                            </div>
                                            <span class="text-xs font-semibold text-slate-600"
                                                x-text="'Rp ' + (detail.price || 0).toLocaleString('id-ID')"></span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 mt-1">
                                            <input type="number" min="0" :max="detail.returnable_quantity"
                                                x-model.number="detail.return_quantity"
                                                :name="detail.selected ? 'items[' + index + '][sale_detail_id]' : null"
                                                class="hidden">
                                            <input type="hidden" :value="detail.id"
                                                :name="detail.selected ? 'items[' + index + '][sale_detail_id]' : ''">
                                            
                                            <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                                                <label class="text-xs text-slate-500 sm:hidden w-10 shrink-0">Qty:</label>
                                                <input type="number" min="1" :max="detail.returnable_quantity"
                                                    x-model.number="detail.return_quantity" placeholder="Qty"
                                                    :disabled="!detail.selected"
                                                    :name="detail.selected ? 'items[' + index + '][quantity]' : ''"
                                                    class="w-full sm:w-20 rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-center text-sm focus:border-blue-500 focus:outline-none disabled:bg-slate-100">
                                            </div>

                                            <div class="flex items-center gap-2 w-full">
                                                <label class="text-xs text-slate-500 sm:hidden w-10 shrink-0">Kondisi:</label>
                                                <select :disabled="!detail.selected" x-model="detail.condition"
                                                    :name="detail.selected ? 'items[' + index + '][condition]' : ''"
                                                    class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none disabled:bg-slate-100">
                                                    <option value="baik">Baik (stok dikembalikan)</option>
                                                    <option value="rusak">Rusak (stok tidak dikembalikan)</option>
                                                </select>
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

                        <button type="submit" :disabled="selectedCount === 0"
                            class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                            Simpan Retur Penjualan
                        </button>
                    </form>
                </template>
            </div>
        </div>
    </div>

    <script>
        function saleReturnForm() {
            return {
                invoiceNumber: '',
                loading: false,
                errorMessage: '',
                sale: null,
                saleDetails: [],
                now: '',
                init() {
                    const d = new Date();
                    d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                    this.now = d.toISOString().slice(0, 16);
                },
                async findSale() {
                    if (!this.invoiceNumber) return;
                    this.loading = true;
                    this.errorMessage = '';
                    this.sale = null;
                    this.saleDetails = [];

                    try {
                        const response = await fetch('{{ route('sale-returns.find-sale') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                invoice_number: this.invoiceNumber
                            }),
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            this.errorMessage = data.message || 'Transaksi tidak ditemukan.';
                            return;
                        }

                        this.sale = data.sale;
                        this.saleDetails = data.sale.sale_details.map(detail => ({
                            ...detail,
                            selected: false,
                            return_quantity: detail.returnable_quantity > 0 ? 1 : 0,
                            condition: 'baik',
                        }));
                    } catch (e) {
                        this.errorMessage = 'Terjadi kesalahan saat mencari transaksi.';
                    } finally {
                        this.loading = false;
                    }
                },
                get selectedCount() {
                    return this.saleDetails.filter(d => d.selected).length;
                },
                get estimatedTotal() {
                    return this.saleDetails
                        .filter(d => d.selected)
                        .reduce((sum, d) => sum + ((d.return_quantity || 0) * (d.price || 0)), 0);
                },
                onSubmit(event) {
                    if (this.selectedCount === 0) {
                        event.preventDefault();
                    }
                },
            };
        }
    </script>
@endsection
