<?php

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;

uses (WithFaker::class);

it('can create an order', function () {
    Product::factory(15)->create();

    $product = Product::inRandomOrder()->first();

    $data = [
        'name' => $this->faker->name,
        'description' => $this->faker->text,
        'date' => date('d/m/Y'),
        'products' => [
            [
                'id' => $product->id,
                'quantity' => $this->faker->numberBetween(1, 10)
            ]
        ]
    ];

    test()->post('/api/orders', $data)
        ->assertSuccessful();
});

it('can update an order', function () {
    Product::factory(15)->create();
    $product = Product::inRandomOrder()->first();
    $product2 = Product::inRandomOrder()->first();

    $data = [
        'name' => $this->faker->name,
        'description' => $this->faker->text,
        'date' => date('d/m/Y'),
        'products' => [
            [
                'id' => $product->id,
                'quantity' => $this->faker->numberBetween(1, 5)
            ]
        ]
    ];

    test()->post('/api/orders', $data)
        ->assertSuccessful();

    $order = Order::latest()->first();

    test()->patch("/api/orders/{$order->id}/update", [
        'name' => 'Order 1 Updated',
        'description' => 'Order 1 Description Updated',
        'date' => date('d/m/Y'),
        'products' => [
            [
                'id' => $product->id,
                'quantity' => 2
            ],
            [
                'id' => $product2->id,
                'quantity' => 4
            ]
        ]
    ])->assertSuccessful();   
});

it('can delete an order', function() {
    $product = Product::factory()->create()->first();

    $data = [
        'name' => $this->faker->name,
        'description' => $this->faker->text,
        'date' => date('d/m/Y'),
        'products' => [
            [
                'id' => $product->id,
                'quantity' => $this->faker->numberBetween(1, 10)
            ]
        ]
    ];

    test()->post('/api/orders', $data)->assertSuccessful();
    // Get the latest order

    $order = Order::latest()->first();

    test()->delete("/api/orders/{$order->id}/delete")
        ->assertSuccessful();
});

test("there are missing parameters to store the order", function() {
    $response = test()->post('/api/orders', [
        'name' => $this->faker->name,
        'description' => $this->faker->text,
        // 'date' is missing
        'products' => [
            [
                'id' => 1,
                'quantity' => $this->faker->numberBetween(1, 10)
            ]
        ]
    ]);

    $response->assertStatus(302);
});

test("product not found", function() {
    $response = test()->post('/api/orders', [
        'name' => $this->faker->name,
        'description' => $this->faker->text,
        'date' => date('d/m/Y'),
        'products' => [
            [
                'id' => 1,
                'quantity' => $this->faker->numberBetween(1, 10)
            ]
        ]
    ]);

    $response->assertStatus(302);
});