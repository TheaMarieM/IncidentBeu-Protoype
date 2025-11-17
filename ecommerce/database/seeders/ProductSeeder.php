<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::truncate();

        Product::create([
            'name' => 'Gaming Laptop',
            'price' => 1299.99,
            'description' => 'High performance gaming laptop with RTX 4060, 16GB RAM, and 1TB SSD',
            'stock' => 15
        ]);

        Product::create([
            'name' => 'iPhone 15 Pro',
            'price' => 999.99,
            'description' => 'Latest iPhone with A17 Pro chip, 128GB storage, and pro camera system',
            'stock' => 25
        ]);

        Product::create([
            'name' => 'Sony WH-1000XM5 Headphones',
            'price' => 349.99,
            'description' => 'Industry-leading noise cancelling wireless headphones',
            'stock' => 30
        ]);

        Product::create([
            'name' => 'Samsung 4K Monitor',
            'price' => 299.99,
            'description' => '27-inch 4K UHD monitor with HDR support and USB-C connectivity',
            'stock' => 12
        ]);

        Product::create([
            'name' => 'Mechanical Keyboard',
            'price' => 129.99,
            'description' => 'RGB backlit mechanical keyboard with Cherry MX switches',
            'stock' => 40
        ]);

        Product::create([
            'name' => 'Wireless Mouse',
            'price' => 59.99,
            'description' => 'Ergonomic wireless mouse with precision sensor and long battery life',
            'stock' => 50
        ]);

        Product::create([
            'name' => 'Webcam HD',
            'price' => 79.99,
            'description' => '1080p HD webcam with auto-focus and built-in microphone',
            'stock' => 20
        ]);

        Product::create([
            'name' => 'Portable SSD 1TB',
            'price' => 149.99,
            'description' => 'Ultra-fast portable SSD with USB 3.2 Gen 2 interface',
            'stock' => 35
        ]);

        Product::create([
            'name' => 'Bluetooth Speaker',
            'price' => 89.99,
            'description' => 'Waterproof Bluetooth speaker with 360-degree sound',
            'stock' => 45
        ]);

        Product::create([
            'name' => 'Tablet 10-inch',
            'price' => 249.99,
            'description' => '10-inch Android tablet with 64GB storage and 8MP camera',
            'stock' => 18
        ]);
    }
}
