@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Riwayat Stok')
@section('page-title', 'Riwayat Stok - ' . $product->name)
@section('page-subtitle', 'SKU: ' . $product->sku . ' | Stok saat ini: ' . $product->stock)

@section('content')
    <div class="mb-4">
        <a href="{{ route('stock') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Stok</a>
    </div>

    <div class="rounded-xl bg-white p-4 shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">Tanggal</th>
                        <th class="py-2">Tipe</th>
                        <th class="py-2 text-right">Jumlah</th>
                        <th class="py-2 text-right">Stok Sebelum</th>
                        <th class="py-2 text-right">Stok Sesudah</th>
                        <th class="py-2">Referensi</th>
                        <th class="py-2">Keterangan</th>
                        <th class="py-2">Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $movement)
                        <tr class="border-b">
                            <td class="py-2">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2">
                                <span
                                    class="rounded px-2 py-0.5 text-xs {{ $movement->type === 'in' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td class="py-2 text-right">{{ $movement->quantity }}</td>
                            <td class="py-2 text-right">{{ $movement->stock_before }}</td>
                            <td class="py-2 text-right">{{ $movement->stock_after }}</td>
                            <td class="py-2 capitalize">{{ $movement->reference_type ?? '-' }}</td>
                            <td class="py-2">{{ $movement->reason ?? $movement->note ?? '-' }}</td>
                            <td class="py-2">{{ $movement->user?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 text-center text-slate-500">Belum ada riwayat stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $movements->links() }}
        </div>
    </div>
@endsection
