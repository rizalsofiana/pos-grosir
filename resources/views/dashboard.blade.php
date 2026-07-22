@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')


@section('title', 'Dashboard POS')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data POS Grosir')

@section('content')
    @if (auth()->user()->isAdmin())
        <div class="grid gap-4 md:grid-cols-4">
            <a href="{{ route('products') }}" class="rounded-xl bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Produk</p>
                <p class="text-2xl font-semibold">{{ $totalProducts }}</p>
            </a>
            <a href="{{ route('categories') }}" class="rounded-xl bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Kategori</p>
                <p class="text-2xl font-semibold">{{ $totalCategories }}</p>
            </a>
            <a href="{{ route('suppliers') }}" class="rounded-xl bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Supplier</p>
                <p class="text-2xl font-semibold">{{ $totalSuppliers }}</p>
            </a>
            <a href="{{ route('customers') }}" class="rounded-xl bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Customer</p>
                <p class="text-2xl font-semibold">{{ $totalCustomers }}</p>
            </a>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2">
            <a href="{{ route('sales') }}" class="rounded-xl bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Transaksi Penjualan</p>
                <p class="text-lg font-semibold">Buka Kasir</p>
            </a>
            <a href="{{ route('purchases') }}" class="rounded-xl bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Transaksi Pembelian</p>
                <p class="text-lg font-semibold">Input Pembelian</p>
            </a>
        </div>
    @endif
@endsection


