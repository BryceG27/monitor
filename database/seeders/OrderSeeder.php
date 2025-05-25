<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 50; $i++) { 
            $order = \App\Models\Order::create([
                'name' => 'Order ' . ($i + 1),
                'description' => 'Description for order ' . ($i + 1),
                'date' => now()->subDays(rand(0, 30))->format('Y-m-d'),
            ]);

            $products = \App\Models\Product::inRandomOrder()->take(rand(1, 5))->get();
            foreach ($products as $product) {
                $order->products()->attach($product->id, ['quantity' => rand(1, 10)]);
            }
        }
    }
}
