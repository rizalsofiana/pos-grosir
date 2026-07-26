<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        return view('purchase-returns.index', [
            'purchaseReturns' => PurchaseReturn::with(['purchase', 'supplier', 'user'])
                ->latest('return_date')
                ->paginate($perPage)
                ->withQueryString(),
            'suppliers' => Supplier::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
            'perPage' => $perPage,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchase_id' => ['nullable', 'exists:purchases,id'],
            'return_date' => ['required', 'date'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($data) {
            $totalAmount = collect($data['items'])->sum(fn ($item) => $item['quantity'] * $item['price']);

            $purchaseReturn = PurchaseReturn::create([
                'purchase_id' => $data['purchase_id'] ?? null,
                'supplier_id' => $data['supplier_id'],
                'user_id' => Auth::id(),
                'return_date' => $data['return_date'],
                'reason' => $data['reason'] ?? null,
                'notes' => $data['notes'] ?? null,
                'total_amount' => $totalAmount,
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    abort(422, "Stok produk {$product->name} tidak mencukupi untuk retur ke supplier.");
                }

                PurchaseReturnDetail::create([
                    'purchase_return_id' => $purchaseReturn->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'sub_total' => $item['quantity'] * $item['price'],
                ]);

                $stockBefore = $product->stock;
                $product->decrement('stock', $item['quantity']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockBefore - $item['quantity'],
                    'reference_type' => 'purchase_return',
                    'reference_id' => $purchaseReturn->id,
                    'reason' => 'Retur pembelian ke supplier',
                    'user_id' => Auth::id(),
                ]);
            }
        });

        return redirect()->route('purchase-returns')->with('success', 'Retur pembelian berhasil disimpan.');
    }
}
