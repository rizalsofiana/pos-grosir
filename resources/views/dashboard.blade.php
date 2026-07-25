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

        <div class="mt-6 rounded-xl bg-white p-6 shadow">
            <div class="mb-4">
                <h2 class="text-base font-semibold text-slate-800">Grafik Penjualan</h2>
                <p class="text-sm text-slate-500">Total penjualan (lunas) 7 hari terakhir</p>
            </div>
            <div class="h-72">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
        <script>
            new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: {
                    labels: @json($salesChart['labels']),
                    datasets: [{
                        label: 'Penjualan',
                        data: @json($salesChart['values']),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: '#2563eb',
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => 'Rp ' + Number(value).toLocaleString('id-ID')
                            },
                            grid: {
                                color: '#e2e8f0'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        </script>
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
