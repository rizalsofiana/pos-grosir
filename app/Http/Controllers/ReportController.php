<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $sales = Sale::with('customer')
            ->whereDate('sale_date', '>=', $startDate)
            ->whereDate('sale_date', '<=', $endDate)
            ->orderByDesc('sale_date')
            ->get();

        $saleDetails = SaleDetail::with('product')
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->whereDate('sale_date', '>=', $startDate)
                    ->whereDate('sale_date', '<=', $endDate);
            })
            ->get();

        $totalOmzet = $sales->sum('grand_amount');
        $totalDiscount = $sales->sum('discount');
        $totalTransactions = $sales->count();

        $hpp = $saleDetails->sum(function ($detail) {
            return $detail->quantity * ($detail->product->purchase_price ?? 0);
        });

        $grossProfit = $totalOmzet - $hpp;

        return view('reports.sales', [
            'sales' => $sales,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalOmzet' => $totalOmzet,
            'totalDiscount' => $totalDiscount,
            'totalTransactions' => $totalTransactions,
            'hpp' => $hpp,
            'grossProfit' => $grossProfit,
        ]);
    }

    public function purchases(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $purchases = Purchase::with('supplier')
            ->whereDate('purchase_date', '>=', $startDate)
            ->whereDate('purchase_date', '<=', $endDate)
            ->orderByDesc('purchase_date')
            ->get();

        return view('reports.purchases', [
            'purchases' => $purchases,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPembelian' => $purchases->sum('total_amount'),
            'totalTransactions' => $purchases->count(),
        ]);
    }

    public function stock(Request $request)
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        $totalStockValue = $products->sum(function ($product) {
            return $product->stock * $product->purchase_price;
        });

        return view('reports.stock', [
            'products' => $products,
            'totalStockValue' => $totalStockValue,
        ]);
    }
}
