<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PosSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $kasirRole = Role::firstOrCreate(['name' => 'kasir']);

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Admin POS',
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@example.com'],
            [
                'role_id' => $kasirRole->id,
                'name' => 'Kasir POS',
                'password' => Hash::make('password123'),
            ]
        );

        $categories = [
            'Minuman',
            'Makanan Ringan',
            'Elektronik',
            'Perawatan',
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }

        $suppliers = [
            ['name' => 'CV Sumber Makmur', 'phone' => '081234567890', 'address' => 'Bandung'],
            ['name' => 'PT Indo Jaya', 'phone' => '082345678901', 'address' => 'Jakarta'],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::firstOrCreate(
                ['name' => $supplierData['name']],
                $supplierData
            );
        }

        $customers = [
            ['name' => 'Budi Santoso', 'phone' => '081111111111', 'address' => 'Bandung'],
            ['name' => 'Siti Aminah', 'phone' => '082222222222', 'address' => 'Surabaya'],
        ];

        foreach ($customers as $customerData) {
            Customer::firstOrCreate(
                ['name' => $customerData['name']],
                $customerData
            );
        }

        $products = [
            ['category_id' => Category::where('name', 'Minuman')->value('id'), 'sku' => 'MIN-001', 'name' => 'Air Mineral 600ml', 'purchase_price' => 2500, 'selling_price' => 4000, 'stock' => 100],
            ['category_id' => Category::where('name', 'Makanan Ringan')->value('id'), 'sku' => 'MKR-001', 'name' => 'Keripik Singkong', 'purchase_price' => 8000, 'selling_price' => 12000, 'stock' => 50],
            ['category_id' => Category::where('name', 'Elektronik')->value('id'), 'sku' => 'ELK-001', 'name' => 'Power Bank 10000mAh', 'purchase_price' => 90000, 'selling_price' => 130000, 'stock' => 20],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );
        }
    }
}
