<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        return view('stock.index', [
            'products' => Product::with('category')->orderBy('name')->get(),
        ]);
    }

    public function history(Product $product)
    {
        return view('stock.history', [
            'product' => $product,
            'movements' => $product->stockMovements()->with('user')->latest()->paginate(20),
        ]);
    }

    public function adjust(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);

            if ($data['type'] === 'out' && $product->stock < $data['quantity']) {
                abort(422, 'Stok tidak mencukupi untuk pengurangan ini.');
            }

            $stockBefore = $product->stock;
            $stockAfter = $data['type'] === 'in'
                ? $stockBefore + $data['quantity']
                : $stockBefore - $data['quantity'];

            $product->update(['stock' => $stockAfter]);

            StockMovement::create([
                'product_id' => $product->id,
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_type' => 'adjustment',
                'reference_id' => null,
                'reason' => $data['reason'],
                'note' => $data['note'] ?? null,
                'user_id' => Auth::id(),
            ]);
        });

        return back()->with('success', 'Penyesuaian stok berhasil disimpan.');
    }
}
