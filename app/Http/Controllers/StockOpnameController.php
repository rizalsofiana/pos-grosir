<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        return view('stock-opnames.index', [
            'stockOpnames' => StockOpname::with('user')
                ->withCount('stockOpnameDetails')
                ->latest('opname_date')
                ->paginate($perPage)
                ->withQueryString(),
            'products' => Product::active()->orderBy('name')->get(),
            'perPage' => $perPage,
        ]);
    }

    public function show(StockOpname $stockOpname)
    {
        return view('stock-opnames.show', [
            'stockOpname' => $stockOpname->load(['user', 'stockOpnameDetails.product']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'opname_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.physical_stock' => ['required', 'integer', 'min:0'],
            'items.*.note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            $stockOpname = StockOpname::create([
                'code' => 'SO-'.now()->format('Ymd-His'),
                'user_id' => Auth::id(),
                'opname_date' => $data['opname_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                $systemStock = $product->stock;
                $physicalStock = $item['physical_stock'];
                $difference = $physicalStock - $systemStock;

                StockOpnameDetail::create([
                    'stock_opname_id' => $stockOpname->id,
                    'product_id' => $product->id,
                    'system_stock' => $systemStock,
                    'physical_stock' => $physicalStock,
                    'difference' => $difference,
                    'note' => $item['note'] ?? null,
                ]);

                if ($difference !== 0) {
                    $product->update(['stock' => $physicalStock]);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => $difference > 0 ? 'in' : 'out',
                        'quantity' => abs($difference),
                        'stock_before' => $systemStock,
                        'stock_after' => $physicalStock,
                        'reference_type' => 'stock_opname',
                        'reference_id' => $stockOpname->id,
                        'reason' => 'Penyesuaian stok opname',
                        'note' => $item['note'] ?? null,
                        'user_id' => Auth::id(),
                    ]);
                }
            }
        });

        return redirect()->route('stock-opnames')->with('success', 'Stok opname berhasil disimpan.');
    }
}
