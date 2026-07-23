@extends('layouts.admin')

@section('title', 'Riwayat Harga')
@section('page-title', 'Riwayat Harga - ' . $product->name)
@section('page-subtitle', 'SKU: ' . $product->sku . ' | Harga beli saat ini: Rp ' . number_format($product->purchase_price, 0, ',', '.') . ' | Harga jual saat ini: Rp ' . number_format($product->selling_price, 0, ',', '.'))

@section('content')
    <div class="mb-4">
        <a href="{{ route('products') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Produk</a>
    </div>

    <div class="rounded-xl bg-white p-4 shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">Tanggal</th>
                        <th class="py-2 text-right">Harga Beli Lama</th>
                        <th class="py-2 text-right">Harga Beli Baru</th>
                        <th class="py-2 text-right">Harga Jual Lama</th>
                        <th class="py-2 text-right">Harga Jual Baru</th>
                        <th class="py-2">Diubah Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $history)
                        <tr class="border-b">
                            <td class="py-2">{{ $history->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($history->old_purchase_price, 0, ',', '.') }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($history->new_purchase_price, 0, ',', '.') }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($history->old_selling_price, 0, ',', '.') }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($history->new_selling_price, 0, ',', '.') }}</td>
                            <td class="py-2">{{ $history->user?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-500">Belum ada riwayat perubahan harga.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $histories->links() }}
        </div>
    </div>
@endsection
