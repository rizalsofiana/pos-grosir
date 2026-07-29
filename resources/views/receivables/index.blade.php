@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Piutang')
@section('page-title', 'Piutang')
@section('page-subtitle', 'Kelola transaksi yang belum lunas dibayar pelanggan')

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

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-800">Daftar Piutang</h2>
                <p class="text-xs text-slate-400">{{ $sales->total() }} transaksi</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="flex rounded-lg border border-slate-200 p-1 text-sm">
                    <a href="{{ route('receivables', ['status' => 'outstanding']) }}"
                        class="rounded-md px-3 py-1.5 font-medium transition {{ $status === 'outstanding' ? 'bg-amber-600 text-white' : 'text-slate-500 hover:bg-slate-100' }}">
                        Belum Lunas
                    </a>
                    <a href="{{ route('receivables', ['status' => 'lunas']) }}"
                        class="rounded-md px-3 py-1.5 font-medium transition {{ $status === 'lunas' ? 'bg-amber-600 text-white' : 'text-slate-500 hover:bg-slate-100' }}">
                        Lunas
                    </a>
                </div>
                <form method="GET" action="{{ route('receivables') }}" class="flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari invoice / nama..."
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">
                    <button type="submit"
                        class="rounded-lg bg-slate-800 px-3 py-2 text-sm font-medium text-white transition hover:bg-slate-900">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        {{-- Desktop / tablet view --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50/90">
                    <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                        <th class="px-5 py-3">Invoice</th>
                        <th class="px-5 py-3">Debitur</th>
                        <th class="px-5 py-3">Jatuh Tempo</th>
                        <th class="px-5 py-3 text-right">Total</th>
                        <th class="px-5 py-3 text-right">Sisa</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($sales as $sale)
                        @php
                            $isOverdue = $sale->due_date && $sale->due_date->isPast() && $sale->payment_status !== 'paid';
                        @endphp
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-5 py-3 font-medium text-slate-700">
                                {{ $sale->invoice_number }}
                                <span class="block text-xs font-normal text-slate-400">
                                    {{ \Illuminate\Support\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                {{ $sale->debtor_name ?? $sale->customer?->name ?? 'Umum' }}
                            </td>
                            <td class="px-5 py-3 {{ $isOverdue ? 'text-red-600 font-medium' : 'text-slate-600' }}">
                                {{ $sale->due_date ? $sale->due_date->format('d/m/Y') : '-' }}
                                @if ($isOverdue)
                                    <span class="block text-xs">Jatuh tempo</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right text-slate-700">
                                Rp {{ number_format($sale->grand_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 text-right font-semibold text-slate-800">
                                Rp {{ number_format($sale->outstanding, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $badge = match ($sale->payment_status) {
                                        'paid' => 'bg-emerald-50 text-emerald-600',
                                        'partial' => 'bg-amber-50 text-amber-600',
                                        default => 'bg-red-50 text-red-600',
                                    };
                                    $label = match ($sale->payment_status) {
                                        'paid' => 'Lunas',
                                        'partial' => 'Sebagian',
                                        default => 'Belum Bayar',
                                    };
                                @endphp
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $badge }}">{{ $label }}</span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('receivables.show', $sale) }}"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-700">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="2" y="5" width="20" height="14" rx="2" />
                                        <path d="M2 10h20" />
                                        <path d="M6 15h4" />
                                    </svg>
                                    <p class="text-sm">Tidak ada data piutang.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile card list view --}}
        <div class="block md:hidden divide-y divide-slate-100">
            @forelse ($sales as $sale)
                @php
                    $isOverdue = $sale->due_date && $sale->due_date->isPast() && $sale->payment_status !== 'paid';
                    $badge = match ($sale->payment_status) {
                        'paid' => 'bg-emerald-50 text-emerald-600',
                        'partial' => 'bg-amber-50 text-amber-600',
                        default => 'bg-red-50 text-red-600',
                    };
                    $label = match ($sale->payment_status) {
                        'paid' => 'Lunas',
                        'partial' => 'Sebagian',
                        default => 'Belum Bayar',
                    };
                @endphp
                <a href="{{ route('receivables.show', $sale) }}" class="block p-4 transition-colors hover:bg-slate-50">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $sale->invoice_number }}</p>
                            <p class="text-xs text-slate-400">{{ $sale->debtor_name ?? $sale->customer?->name ?? 'Umum' }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $badge }}">{{ $label }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-y-1 text-xs text-slate-500">
                        <span>Jatuh Tempo</span>
                        <span class="text-right {{ $isOverdue ? 'text-red-600 font-medium' : 'text-slate-700 font-medium' }}">
                            {{ $sale->due_date ? $sale->due_date->format('d/m/Y') : '-' }}
                        </span>
                        <span>Sisa Piutang</span>
                        <span class="text-right font-semibold text-slate-800">
                            Rp {{ number_format($sale->outstanding, 0, ',', '.') }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center gap-2 px-5 py-14 text-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <path d="M2 10h20" />
                        <path d="M6 15h4" />
                    </svg>
                    <p class="text-sm">Tidak ada data piutang.</p>
                </div>
            @endforelse
        </div>

        <div class="flex flex-col items-center gap-3 border-t border-slate-100 px-5 py-3 text-sm text-slate-500 sm:flex-row sm:justify-between">
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
