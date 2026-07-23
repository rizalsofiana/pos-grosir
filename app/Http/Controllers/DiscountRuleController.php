<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DiscountRule;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountRuleController extends Controller
{
    public function index()
    {
        return view('discount-rules.index', [
            'rules' => DiscountRule::with(['product', 'category'])->latest()->get(),
            'products' => Product::active()->orderBy('name')->get(),
            'categories' => Category::active()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        DiscountRule::create($data);

        return back()->with('success', 'Aturan diskon berhasil ditambahkan.');
    }

    public function update(Request $request, DiscountRule $discountRule)
    {
        $data = $this->validated($request);
        $discountRule->update($data);

        return back()->with('success', 'Aturan diskon berhasil diperbarui.');
    }

    public function toggle(DiscountRule $discountRule)
    {
        $discountRule->update(['is_active' => ! $discountRule->is_active]);

        return back()->with('success', 'Status aturan diskon berhasil diperbarui.');
    }

    public function destroy(DiscountRule $discountRule)
    {
        $discountRule->delete();

        return back()->with('success', 'Aturan diskon berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'scope' => ['required', 'in:product,category'],
            'product_id' => ['required_if:scope,product', 'nullable', 'exists:products,id'],
            'category_id' => ['required_if:scope,category', 'nullable', 'exists:categories,id'],
            'min_qty' => ['required', 'integer', 'min:1'],
            'discount_type' => ['required', 'in:percentage,nominal'],
            'discount_value' => ['required', 'numeric', 'min:0'],
        ]);

        if ($data['scope'] === 'product') {
            $data['category_id'] = null;
        } else {
            $data['product_id'] = null;
        }

        return $data;
    }
}
