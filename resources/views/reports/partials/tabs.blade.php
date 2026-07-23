<div class="mb-6 flex gap-2 border-b border-slate-200">
    <a href="{{ route('reports.sales') }}"
        class="border-b-2 px-4 py-2 text-sm font-medium {{ request()->routeIs('reports.sales') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
        Penjualan
    </a>
    <a href="{{ route('reports.purchases') }}"
        class="border-b-2 px-4 py-2 text-sm font-medium {{ request()->routeIs('reports.purchases') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
        Pembelian
    </a>
    <a href="{{ route('reports.stock') }}"
        class="border-b-2 px-4 py-2 text-sm font-medium {{ request()->routeIs('reports.stock') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
        Stok
    </a>
</div>
