<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function products()
    {
        return view('master-data.products', [
            'products' => Product::with('category')->latest()->get(),
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
}
