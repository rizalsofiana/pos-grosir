@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Detail Piutang')
@section('page-title', 'Detail Piutang')
@section('page-subtitle', $sale->invoice_number)

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

    <div class="mb-6">
        <a href="{{ route('receivables') }}" class="inline-flex items-center gap-1 text-sm font-medium text-slate-500 hover:text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6" />
            </svg>
            Kembali ke Daftar Piutang
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.1fr_1fr] xl:grid-cols-[1.3fr_1fr]">
        {{-- Detail Transaksi --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="font-semibold text-slate-800">{{ $sale->invoice_number }}</h2>
                    <p class="text-xs text-slate-400">
                        {{ \Illuminate\Support\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}
                    </p>
                </div>
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
                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $badge }}">{{ $label }}</span>
            </div>

            <div class="grid grid-cols-2 gap-y-2 px-5 py-4 text-sm">
                <span class="text-slate-500">Debitur</span>
                <span class="text-right font-medium text-slate-700">{{ $sale->debtor_name ?? $sale->customer?->name ?? 'Umum' }}</span>
                <span class="text-slate-500">Kasir</span>
                <span class="text-right font-medium text-slate-700">{{ $sale->user?->name ?? '-' }}</span>
                <span class="text-slate-500">Jatuh Tempo</span>
                <span class="text-right font-medium {{ $sale->due_date && $sale->due_date->isPast() && $sale->payment_status !== 'paid' ? 'text-red-600' : 'text-slate-700' }}">
                    {{ $sale->due_date ? $sale->due_date->format('d/m/Y') : '-' }}
                </span>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                <p class="mb-2 text-xs font-medium uppercase tracking-wide text-slate-400">Item Belanja</p>
                <div class="divide-y divide-slate-100">
                    @foreach ($sale->saleDetails as $detail)
                        <div class="flex items-center justify-between py-2 text-sm">
                            <div>
                                <p class="font-medium text-slate-700">{{ $detail->product?->name }}</p>
                                <p class="text-xs text-slate-400">{{ $detail->quantity }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</p>
                            </div>
                            <span class="font-medium text-slate-700">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-slate-100 px-5 py-4 space-y-1 text-sm">
                <div class="flex justify-between text-slate-500">
                    <span>Sub Total</span>
                    <span>Rp {{ number_format($sale->sub_total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Diskon</span>
                    <span>Rp {{ number_format($sale->discount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-semibold text-slate-800">
                    <span>Total Belanja</span>
                    <span>Rp {{ number_format($sale->grand_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Sudah Dibayar</span>
                    <span>Rp {{ number_format($sale->grand_amount - $sale->outstanding, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-base font-bold text-red-600">
                    <span>Sisa Piutang</span>
                    <span>Rp {{ number_format($sale->outstanding, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Form Bayar & Riwayat Pembayaran --}}
        <div class="space-y-6">
            @if ($sale->outstanding > 0)
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="font-semibold text-slate-800">Bayar Piutang</h2>
                    </div>
                    <form method="POST" action="{{ route('receivables.pay', $sale) }}" class="space-y-4 px-5 py-5">
                        @csrf
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-500">Jumlah Bayar</label>
                            <input type="number" name="amount" min="1" max="{{ $sale->outstanding }}" step="1"
                                value="{{ old('amount') }}" required
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">
                            @error('amount')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-500">Catatan (opsional)</label>
                            <input type="text" name="note" value="{{ old('note') }}" placeholder="Catatan pembayaran"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">
                        </div>
                        <button type="submit"
                            class="w-full rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-700">
                            Catat Pembayaran
                        </button>
                    </form>
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="font-semibold text-slate-800">Riwayat Pembayaran</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($sale->payments as $payment)
                        <div class="flex items-center justify-between px-5 py-3 text-sm">
                            <div>
                                <p class="font-medium text-slate-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-400">
                                    {{ $payment->paid_at->format('d/m/Y H:i') }} &middot; {{ $payment->user?->name ?? '-' }}
                                </p>
                                @if ($payment->note)
                                    <p class="text-xs text-slate-400">{{ $payment->note }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center text-sm text-slate-400">Belum ada pembayaran.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
