<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleReturnController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        return view('sale-returns.index', [
            'saleReturns' => SaleReturn::with(['sale', 'customer', 'user'])
                ->latest('return_date')
                ->paginate($perPage)
                ->withQueryString(),
            'perPage' => $perPage,
        ]);
    }

    public function findSale(Request $request)
    {
        $data = $request->validate([
            'invoice_number' => ['required', 'string'],
        ]);

        $sale = Sale::with(['customer', 'saleDetails.product'])
            ->where('invoice_number', $data['invoice_number'])
            ->first();

        if (! $sale) {
            return response()->json(['message' => 'Transaksi penjualan tidak ditemukan.'], 404);
        }

        $returnedQuantities = SaleReturnDetail::whereIn(
            'sale_detail_id',
            $sale->saleDetails->pluck('id')
        )
            ->selectRaw('sale_detail_id, SUM(quantity) as total_returned')
            ->groupBy('sale_detail_id')
            ->pluck('total_returned', 'sale_detail_id');

        $sale->setRelation('saleDetails', $sale->saleDetails->map(function ($detail) use ($returnedQuantities) {
            $detail->already_returned = $returnedQuantities[$detail->id] ?? 0;
            $detail->returnable_quantity = $detail->quantity - $detail->already_returned;

            return $detail;
        }));

        return response()->json(['sale' => $sale]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sale_id' => ['required', 'exists:sales,id'],
            'return_date' => ['required', 'date'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sale_detail_id' => ['required', 'exists:sale_details,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.condition' => ['required', 'in:baik,rusak'],
        ]);

        DB::transaction(function () use ($data) {
            $sale = Sale::with('saleDetails')->lockForUpdate()->findOrFail($data['sale_id']);
            $saleDetails = $sale->saleDetails->keyBy('id');

            $alreadyReturned = SaleReturnDetail::whereIn('sale_detail_id', $saleDetails->keys())
                ->selectRaw('sale_detail_id, SUM(quantity) as total_returned')
                ->groupBy('sale_detail_id')
                ->pluck('total_returned', 'sale_detail_id');

            $totalAmount = 0;

            foreach ($data['items'] as $item) {
                $saleDetail = $saleDetails[$item['sale_detail_id']] ?? null;

                if (! $saleDetail) {
                    abort(422, 'Detail transaksi tidak ditemukan.');
                }

                $returned = $alreadyReturned[$saleDetail->id] ?? 0;
                $returnable = $saleDetail->quantity - $returned;

                if ($item['quantity'] > $returnable) {
                    abort(422, "Jumlah retur untuk produk melebihi sisa yang bisa diretur ({$returnable}).");
                }

                $totalAmount += $item['quantity'] * $saleDetail->price;
            }

            $saleReturn = SaleReturn::create([
                'sale_id' => $sale->id,
                'customer_id' => $sale->customer_id,
                'user_id' => Auth::id(),
                'return_date' => $data['return_date'],
                'reason' => $data['reason'] ?? null,
                'notes' => $data['notes'] ?? null,
                'total_amount' => $totalAmount,
            ]);

            foreach ($data['items'] as $item) {
                $saleDetail = $saleDetails[$item['sale_detail_id']];
                $subTotal = $item['quantity'] * $saleDetail->price;

                SaleReturnDetail::create([
                    'sale_return_id' => $saleReturn->id,
                    'sale_detail_id' => $saleDetail->id,
                    'product_id' => $saleDetail->product_id,
                    'quantity' => $item['quantity'],
                    'price' => $saleDetail->price,
                    'sub_total' => $subTotal,
                    'condition' => $item['condition'],
                ]);

                if ($item['condition'] === 'baik') {
                    $product = Product::lockForUpdate()->find($saleDetail->product_id);
                    $stockBefore = $product->stock;
                    $product->increment('stock', $item['quantity']);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $item['quantity'],
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockBefore + $item['quantity'],
                        'reference_type' => 'sale_return',
                        'reference_id' => $saleReturn->id,
                        'reason' => 'Retur penjualan - kondisi baik',
                        'user_id' => Auth::id(),
                    ]);
                }
            }
        });

        return redirect()->route('sale-returns')->with('success', 'Retur penjualan berhasil disimpan.');
    }
}
