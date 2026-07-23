<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\PriceHistory;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MasterDataController extends Controller
{
    public function products()
    {
        return view('master-data.products', [
            'products' => Product::with('category')->latest()->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function categories()
    {
        return view('master-data.categories', [
            'categories' => Category::latest()->get(),
        ]);
    }

    public function suppliers()
    {
        return view('master-data.suppliers', [
            'suppliers' => Supplier::latest()->get(),
        ]);
    }

    public function customers()
    {
        return view('master-data.customers', [
            'customers' => Customer::latest()->get(),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100']]);
        Category::create($data);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100']]);
        $category->update($data);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggleCategory(Category $category)
    {
        $category->update(['is_active' => ! $category->is_active]);

        return back()->with('success', 'Status kategori berhasil diperbarui.');
    }

    public function storeSupplier(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
        Supplier::create($data);

        return back()->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function updateSupplier(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
        $supplier->update($data);

        return back()->with('success', 'Supplier berhasil diperbarui.');
    }

    public function toggleSupplier(Supplier $supplier)
    {
        $supplier->update(['is_active' => ! $supplier->is_active]);

        return back()->with('success', 'Status supplier berhasil diperbarui.');
    }

    public function storeCustomer(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
        Customer::create($data);

        return back()->with('success', 'Customer berhasil ditambahkan.');
    }

    public function updateCustomer(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
        $customer->update($data);

        return back()->with('success', 'Customer berhasil diperbarui.');
    }

    public function toggleCustomer(Customer $customer)
    {
        $customer->update(['is_active' => ! $customer->is_active]);

        return back()->with('success', 'Status customer berhasil diperbarui.');
    }

    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:100'],
            'purchase_price' => ['required', 'numeric'],
            'selling_price' => ['required', 'numeric'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);
        Product::create($data);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:100'],
            'purchase_price' => ['required', 'numeric'],
            'selling_price' => ['required', 'numeric'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $priceChanged = (float) $product->purchase_price !== (float) $data['purchase_price']
            || (float) $product->selling_price !== (float) $data['selling_price'];

        if ($priceChanged) {
            PriceHistory::create([
                'product_id' => $product->id,
                'old_purchase_price' => $product->purchase_price,
                'new_purchase_price' => $data['purchase_price'],
                'old_selling_price' => $product->selling_price,
                'new_selling_price' => $data['selling_price'],
                'user_id' => Auth::id(),
            ]);
        }

        $product->update($data);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function toggleProduct(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('success', 'Status produk berhasil diperbarui.');
    }

    public function priceHistory(Product $product)
    {
        return view('master-data.price-history', [
            'product' => $product,
            'histories' => $product->priceHistories()->with('user')->latest()->paginate(20),
        ]);
    }
}


