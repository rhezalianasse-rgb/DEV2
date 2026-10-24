<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_reads_remain_public(): void
    {
        $product = Product::create([
            'name' => 'Product',
            'description' => null,
            'price' => 12.50,
        ]);

        $this->getJson('/api/products')->assertOk();
        $this->getJson("/api/products/{$product->id}")->assertOk();
    }

    public function test_product_creation_is_public_but_updates_and_deletes_require_authentication(): void
    {
        $product = Product::create([
            'name' => 'Product',
            'description' => null,
            'price' => 12.50,
        ]);

        $this->postJson('/api/products', [
            'name' => 'New product',
            'price' => 10,
        ])->assertCreated();

        $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated product',
        ])->assertUnauthorized();

        $this->deleteJson("/api/products/{$product->id}")->assertUnauthorized();
    }

    public function test_price_must_fit_the_products_decimal_column(): void
    {
        $this->postJson('/api/products', [
            'name' => 'Valid product',
            'price' => '999999.99',
        ])->assertCreated()
            ->assertJsonPath('price', '999999.99');

        foreach (['1000000', '1.234'] as $price) {
            $this->postJson('/api/products', [
                'name' => 'Invalid product',
                'price' => $price,
            ])->assertUnprocessable()
                ->assertJsonValidationErrors('price');
        }
    }
}
