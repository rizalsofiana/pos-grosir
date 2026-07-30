@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')
@section('page-subtitle', 'Riwayat lengkap semua transaksi penjualan')

@section('content')
    <form method="GET"
        class="mb-6 grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:flex lg:flex-wrap lg:items-end">
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        <div class="sm:col-span-2 lg:col-span-1">
            <label class="mb-1 block text-xs font-medium text-slate-500">Cari Invoice</label>
            <input type="text" name="search" value="{{ $search }}" placeholder="INV-..."
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        <input type="hidden" name="per_page" value="{{ $perPage }}">
        <button type="submit"
            class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 sm:col-span-2 sm:w-auto lg:col-span-1">Filter</button>
    </form>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-800">Daftar Transaksi</h2>
                <p class="text-xs text-slate-400">{{ $sales->total() }} transaksi pada periode ini</p>
            </div>
            <form method="GET" class="flex items-center gap-2 text-sm text-slate-500">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <input type="hidden" name="search" value="{{ $search }}">
                <label for="per_page">Tampilkan</label>
                <select id="per_page" name="per_page" onchange="this.form.submit()"
                    class="rounded-lg border border-slate-200 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none">
                    @foreach ([10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                            {{ $option }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Desktop / tablet table --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                        <th class="px-5 py-3">Invoice</th>
                        <th class="px-5 py-3">Tanggal</th>
                        {{-- <th class="px-5 py-3">Customer</th> --}}
                        <th class="px-5 py-3">Kasir</th>
                        <th class="px-5 py-3">Metode Bayar</th>
                        <th class="px-5 py-3 text-right">Diskon</th>
                        <th class="px-5 py-3 text-right">Total</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($sales as $sale)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-5 py-3 font-medium text-slate-700">{{ $sale->invoice_number }}</td>
                            <td class="px-5 py-3 text-slate-500">{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                            {{-- <td class="px-5 py-3 text-slate-500">{{ $sale->customer?->name ?? '-' }}</td> --}}
                            <td class="px-5 py-3 text-slate-500">{{ $sale->user?->name ?? '-' }}</td>
                            <td class="px-5 py-3 capitalize text-slate-500">{{ $sale->payment_method }}</td>
                            <td class="px-5 py-3 text-right text-slate-500">Rp
                                {{ number_format($sale->discount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-slate-800">Rp
                                {{ number_format($sale->grand_amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3">
                                <a href="{{ route('sales.receipt', $sale) }}" target="_blank"
                                    class="font-medium text-blue-600 hover:underline">Struk</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="16" rx="2" />
                                        <path d="M8 2v4M16 2v4M3 10h18" />
                                    </svg>
                                    <p class="text-sm">Tidak ada transaksi pada periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile card list --}}
        <div class="divide-y divide-slate-100 md:hidden">
            @forelse ($sales as $sale)
                <div class="p-4">
                    <div class="mb-2 flex items-start justify-between gap-2">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $sale->invoice_number }}</p>
                            <p class="text-xs text-slate-400">{{ $sale->sale_date->format('d/m/Y H:i') }}</p>
                        </div>
                        <span
                            class="shrink-0 rounded-full px-2 py-0.5 text-xs capitalize {{ $sale->payment_method === 'cash' ? 'bg-slate-100 text-slate-600' : 'bg-blue-50 text-blue-600' }}">
                            {{ $sale->payment_method }}
                        </span>
                    </div>
                    <div class="mb-2 grid grid-cols-2 gap-y-1 text-xs text-slate-500">
                        <span>Customer</span>
                        <span class="text-right text-slate-700">{{ $sale->customer?->name ?? '-' }}</span>
                        <span>Kasir</span>
                        <span class="text-right text-slate-700">{{ $sale->user?->name ?? '-' }}</span>
                        <span>Diskon</span>
                        <span class="text-right text-slate-700">Rp
                            {{ number_format($sale->discount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-dashed border-slate-200 pt-2">
                        <span class="text-sm font-semibold text-slate-800">Rp
                            {{ number_format($sale->grand_amount, 0, ',', '.') }}</span>
                        <a href="{{ route('sales.receipt', $sale) }}" target="_blank"
                            class="text-sm font-medium text-blue-600 hover:underline">Lihat Struk</a>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center gap-2 px-5 py-14 text-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="2" />
                        <path d="M8 2v4M16 2v4M3 10h18" />
                    </svg>
                    <p class="text-sm">Tidak ada transaksi pada periode ini.</p>
                </div>
            @endforelse
        </div>

        <div
            class="flex flex-col items-center gap-3 border-t border-slate-100 px-5 py-3 text-sm text-slate-500 sm:flex-row sm:justify-between">
            <span class="text-center sm:text-left">
                Menampilkan {{ $sales->firstItem() ?? 0 }}-{{ $sales->lastItem() ?? 0 }}
                dari {{ $sales->total() }} data
            </span>
            <div>
                {{ $sales->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
@endsection
