<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder {
    public function run(): void {
        $categories = [
            ['name' => 'Hot Coffee',     'slug' => 'hot-coffee',     'description' => 'Freshly brewed, rich, and comforting hot coffee.', 'is_active' => true],
            ['name' => 'Iced Coffee',    'slug' => 'iced-coffee',    'description' => 'Smooth and refreshing cold coffee served over ice.', 'is_active' => true],
            ['name' => 'Frappe',         'slug' => 'frappe',         'description' => 'Creamy blended drinks with a sweet and icy twist.', 'is_active' => true],
            ['name' => 'Non-Coffee',     'slug' => 'non-coffee',     'description' => 'Delicious drinks for those who prefer no coffee.', 'is_active' => true],
            ['name' => 'Pastries',       'slug' => 'pastries',       'description' => 'Freshly baked breads and delightful sweet treats.', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}