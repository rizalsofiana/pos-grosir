<?php

namespace App\Http\Controllers;

use App\Exports\PurchasesExport;
use App\Exports\SalesExport;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
    private function perPage(Request $request): int
    {
        $perPage = (int) $request->input('per_page', 10);

        return in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;
    }

    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $perPage = $this->perPage($request);

        $salesQuery = Sale::with('customer')
            ->whereDate('sale_date', '>=', $startDate)
            ->whereDate('sale_date', '<=', $endDate);

        $allSales = (clone $salesQuery)->get();

        $saleDetails = SaleDetail::with('product')
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->whereDate('sale_date', '>=', $startDate)
                    ->whereDate('sale_date', '<=', $endDate);
            })
            ->get();

        $totalOmzet = $allSales->sum('grand_amount');
        $totalDiscount = $allSales->sum('discount');
        $totalTransactions = $allSales->count();

        $hpp = $saleDetails->sum(function ($detail) {
            return $detail->quantity * ($detail->product->purchase_price ?? 0);
        });

        $grossProfit = $totalOmzet - $hpp;

        $sales = $salesQuery->orderByDesc('sale_date')->paginate($perPage)->withQueryString();

        return view('reports.sales', [
            'sales' => $sales,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalOmzet' => $totalOmzet,
            'totalDiscount' => $totalDiscount,
            'totalTransactions' => $totalTransactions,
            'hpp' => $hpp,
            'grossProfit' => $grossProfit,
            'perPage' => $perPage,
        ]);
    }

    public function purchases(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $perPage = $this->perPage($request);

        $purchasesQuery = Purchase::with('supplier')
            ->whereDate('purchase_date', '>=', $startDate)
            ->whereDate('purchase_date', '<=', $endDate);

        $allPurchases = (clone $purchasesQuery)->get();

        $purchases = $purchasesQuery->orderByDesc('purchase_date')->paginate($perPage)->withQueryString();

        return view('reports.purchases', [
            'purchases' => $purchases,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPembelian' => $allPurchases->sum('total_amount'),
            'totalTransactions' => $allPurchases->count(),
            'perPage' => $perPage,
        ]);
    }

    public function exportSales(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        return Excel::download(new SalesExport($startDate, $endDate), "laporan-penjualan-{$startDate}-{$endDate}.xlsx");
    }

    public function exportPurchases(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        return Excel::download(new PurchasesExport($startDate, $endDate), "laporan-pembelian-{$startDate}-{$endDate}.xlsx");
    }

    public function stock(Request $request)
    {

        $perPage = $this->perPage($request);

        $allProducts = Product::orderBy('name')->get();

        $totalStockValue = $allProducts->sum(function ($product) {
            return $product->stock * $product->purchase_price;
        });

        $products = Product::with('category')->orderBy('name')->paginate($perPage)->withQueryString();

        return view('reports.stock', [
            'products' => $products,
            'totalStockValue' => $totalStockValue,
            'totalProducts' => $allProducts->count(),
            'perPage' => $perPage,
        ]);
    }
}
