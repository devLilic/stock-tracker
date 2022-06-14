<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TrackCommandTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function it_track_product_stock()
    {
        // Given
        // I have a product witih stock
        $this->seed(RetailerWithProductSeeder::class);

        $this->assertFalse(Product::first()->inStock());

        Http::fake(fn() => ['available' => true, 'price' => 29000]);

        //When
        // I trigger the artisan track command
        // And assuming the stock is available now
        $this->artisan('track')
            ->expectsOutput('All done.');

        // Then
        // The stock details must be refreshed
        $this->assertTrue(Product::first()->inStock());
    }
}
