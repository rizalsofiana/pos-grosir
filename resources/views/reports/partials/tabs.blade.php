<div class="mb-6 flex w-fit max-w-full overflow-x-auto gap-1 rounded-xl border border-slate-200 bg-white p-1 shadow-sm whitespace-nowrap">
    <a href="{{ route('reports.sales') }}"
        class="rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('reports.sales') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
        Penjualan
    </a>
    <a href="{{ route('reports.purchases') }}"
        class="rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('reports.purchases') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
        Pembelian
    </a>
    <a href="{{ route('reports.stock') }}"
        class="rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('reports.stock') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
        Stok
    </a>
</div>
