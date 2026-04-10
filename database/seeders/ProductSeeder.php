<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder {
    public function run(): void {
        $products = [
            ['category_id' => 1, 'name' => 'Americano',       'slug' => 'americano',       'price' => 99,  'stock' => 50, 'size_options' => ['Small', 'Medium', 'Large'], 'is_available' => true],
            ['category_id' => 1, 'name' => 'Cappuccino',      'slug' => 'cappuccino',      'price' => 120, 'stock' => 50, 'size_options' => ['Small', 'Medium', 'Large'], 'is_available' => true],
            ['category_id' => 1, 'name' => 'Latte',           'slug' => 'latte',           'price' => 130, 'stock' => 50, 'size_options' => ['Small', 'Medium', 'Large'], 'is_available' => true],
            ['category_id' => 2, 'name' => 'Iced Americano',  'slug' => 'iced-americano',  'price' => 110, 'stock' => 50, 'size_options' => ['Medium', 'Large'],          'is_available' => true],
            ['category_id' => 2, 'name' => 'Iced Latte',      'slug' => 'iced-latte',      'price' => 140, 'stock' => 50, 'size_options' => ['Medium', 'Large'],          'is_available' => true],
            ['category_id' => 3, 'name' => 'Mocha Frappe',    'slug' => 'mocha-frappe',    'price' => 150, 'stock' => 30, 'size_options' => ['Medium', 'Large'],          'is_available' => true],
            ['category_id' => 3, 'name' => 'Caramel Frappe',  'slug' => 'caramel-frappe',  'price' => 155, 'stock' => 30, 'size_options' => ['Medium', 'Large'],          'is_available' => true],
            ['category_id' => 4, 'name' => 'Matcha Latte',    'slug' => 'matcha-latte',    'price' => 135, 'stock' => 40, 'size_options' => ['Small', 'Medium', 'Large'], 'is_available' => true],
            ['category_id' => 4, 'name' => 'Chocolate Milk',  'slug' => 'chocolate-milk',  'price' => 110, 'stock' => 40, 'size_options' => ['Small', 'Medium', 'Large'], 'is_available' => true],
            ['category_id' => 5, 'name' => 'Croissant',       'slug' => 'croissant',       'price' => 75,  'stock' => 20, 'size_options' => null,                         'is_available' => true],
            ['category_id' => 5, 'name' => 'Blueberry Muffin','slug' => 'blueberry-muffin','price' => 85,  'stock' => 20, 'size_options' => null,                         'is_available' => true],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'description' => 'Masarap na ' . $product['name'],
                'size_options' => json_encode($product['size_options']),
            ]));
        }
    }
}