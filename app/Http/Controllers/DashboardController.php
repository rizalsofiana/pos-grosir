<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalSuppliers' => Supplier::count(),
            'totalCustomers' => Customer::count(),
        ];

        if ($request->user()->isAdmin()) {
            $data['salesChart'] = $this->salesChartData();
        }

        return view('dashboard', $data);
    }

    private function salesChartData(): array
    {
        $days = collect(range(6, 0))->map(fn($i) => Carbon::today('Asia/Jakarta')->subDays($i));

        $sales = Sale::selectRaw('DATE(sale_date) as date, SUM(grand_amount) as total')
            ->where('payment_status', 'paid')
            ->where('sale_date', '>=', $days->first())
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'labels' => $days->map(fn($d) => $d->translatedFormat('d M'))->values()->all(),
            'values' => $days->map(fn($d) => (float) ($sales[$d->toDateString()] ?? 0))->values()->all(),
        ];
    }
}
