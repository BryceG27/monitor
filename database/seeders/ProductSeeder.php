<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 products using the ProductFactory
        \App\Models\Product::factory(50)->create();
        
        // Optionally, you can create a specific product for testing purposes
        // \App\Models\Product::factory()->create([
        //     'name' => 'Test Product',
        //     'price' => 99.99,
        // ]);
    }
}
