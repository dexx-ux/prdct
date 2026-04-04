<?php

use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('does not show out-of-stock products on the index page', function () {
    Product::factory()->create(["quantity" => 0, "name" => "gone"]);
    Product::factory()->create(["quantity" => 5, "name" => "in stock"]);

    $response = $this->actingAs($this->user)->get(route('products.index'));

    $response->assertStatus(200);
    $response->assertSee('in stock');
    $response->assertDontSee('gone');
});

it('can filter to only low stock products using threshold', function () {
    Product::factory()->create(["quantity" => 2, "name" => "low"]);
    Product::factory()->create(["quantity" => 10, "name" => "plenty"]);

    $response = $this->actingAs($this->user)->get(route('products.index', ['filter' => 'low', 'threshold' => 5]));

    $response->assertStatus(200);
    $response->assertSee('low');
    $response->assertDontSee('plenty');
});

it('can filter to only out of stock products', function () {
    Product::factory()->create(["quantity" => 0, "name" => "gone"]);
    Product::factory()->create(["quantity" => 3, "name" => "here"]);

    $response = $this->actingAs($this->user)->get(route('products.index', ['filter' => 'out']));

    $response->assertStatus(200);
    $response->assertSee('gone');
    $response->assertDontSee('here');
});

it('rejects invalid discount formats', function () {
    $data = [
        'name' => 'foo',
        'quantity' => 1,
        'price' => 100,
        'discount_value' => 'not a number',
    ];

    $response = $this->actingAs($this->user)->post(route('products.store'), $data);
    $response->assertSessionHasErrors('discount_value');
});

it('does not allow percentage greater than 100', function () {
    $data = [
        'name' => 'foo',
        'quantity' => 1,
        'price' => 200,
        'discount_value' => '150%',
    ];

    $response = $this->actingAs($this->user)->post(route('products.store'), $data);
    $response->assertSessionHasErrors('discount_value');
});

it('does not allow fixed discount greater than price', function () {
    $data = [
        'name' => 'foo',
        'quantity' => 1,
        'price' => 10,
        'discount_value' => '20',
    ];

    $response = $this->actingAs($this->user)->post(route('products.store'), $data);
    $response->assertSessionHasErrors('price');
});

it('allows creating a product without any discount', function () {
    $data = [
        'name' => 'bar',
        'quantity' => 3,
        'price' => 50,
        'discount_value' => '',
    ];

    $response = $this->actingAs($this->user)->post(route('products.store'), $data);
    $response->assertRedirect(route('products.index'));
    $this->assertDatabaseHas('products', ['name' => 'bar', 'discount_value' => null]);
});

it('handles percentage discounts correctly', function () {
    $data = [
        'name' => 'foo',
        'quantity' => 1,
        'price' => 100,
        'discount_value' => '10%',
    ];

    $response = $this->actingAs($this->user)->post(route('products.store'), $data);
    $response->assertRedirect(route('products.index'));
    $product = Product::where('name', 'foo')->first();
    expect($product->final_price)->toBe(90);
});

it('handles fixed discounts correctly', function () {
    $data = [
        'name' => 'baz',
        'quantity' => 1,
        'price' => 100,
        'discount_value' => '20',
    ];

    $response = $this->actingAs($this->user)->post(route('products.store'), $data);
    $response->assertRedirect(route('products.index'));
    $product = Product::where('name', 'baz')->first();
    expect($product->final_price)->toBe(80);
});
