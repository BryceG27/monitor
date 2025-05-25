<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;

uses (WithFaker::class);

// 
it('can create a product', function () {
    $data = [
        'name' => $this->faker->name,
        'price' => $this->faker->randomFloat(3),
        'quantity' => $this->faker->numberBetween(0, 100),
    ];

    test()->post('/api/products', [
        'name' => $data['name'],
        'price' => $data['price']
    ])->assertSuccessful()->assertJson([
        'name' => $data['name'],
        'price' => $data['price']
    ]);
});

it('can update a product', function () {
    $data = [
        'name' => $this->faker->name,
        'price' => $this->faker->randomFloat(3)
    ];

    test()->post('/api/products', [
        'name' => $data['name'],
        'price' => $data['price']
    ]);

    $product = Product::orderBy('created_at', 'desc')->first();

    $product_json = [
        'name' => $data['name'],
        'price' => $data['price']
    ];

    test()->patch("/api/products/{$product->id}/update", $product_json)
    ->assertSuccessful()
    ->assertJson($product_json);
});

it('can delete a product', function() {
    $data = [
        'name' => $this->faker->name,
        'price' => $this->faker->randomFloat(3)
    ];

    test()->post('/api/products', [
        'name' => $data['name'],
        'price' => $data['price']
    ]);

    $product = Product::orderBy('created_at', 'desc')->first();

    test()->delete("/api/products/{$product->id}/delete")
        ->assertSuccessful();
});

it('cannot delete a product if it is in an order', function () {
    $product = Product::factory()->create();

    // Crea un ordine e associa il prodotto
    $order = \App\Models\Order::create([
        'name' => $this->faker->name,
        'description' => $this->faker->sentence,
        'date' => now(),
    ]);
    $order->products()->attach($product->id);

    $response = test()->delete("/api/products/{$product->id}/delete", [], ['Accept' => 'application/json']);

    $response->assertStatus(400)
        ->assertJson([
            'message' => 'Cannot delete product with existing orders',
        ]);
});

test('there are missing parameters for the store of the product', function () {
    $response = test()->post('/api/products', [
        'price' => $this->faker->randomFloat(3)
    ], ['Accept' => 'application/json']);

    $response->assertStatus(422)->assertJsonValidationErrors('name');
});