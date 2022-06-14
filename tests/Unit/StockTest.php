<?php

namespace Tests\Unit;

use App\Clients\Client;
use App\Clients\ClientException;
use App\Clients\StockStatus;
use App\Models\Retailer;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Facades\App\Clients\ClientFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throws_an_exception_if_a_client_is_not_available_when_tracking()
    {
        $this->seed(RetailerWithProductSeeder::class);

        Retailer::first()->update(['name' => 'Foo Retailer']);

        $this->expectException(ClientException::class);

        Stock::first()->track();
    }

    /** @test */
    public function it_updates_local_stock_status_after_being_tracked()
    {
        $this->seed(RetailerWithProductSeeder::class);

        // ClientFactory to determine the appropriate Client
        // checkAvailability()

        // 1 variant of test (it uses new FakeClass declared after current test)
//        ClientFactory::shouldReceive('make')->andReturn(new FakeClient);

        // 2 variant (using Mockery)
        $clientMock = \Mockery::mock(Client::class);
        $clientMock->shouldReceive('make')->andReturn(new StockStatus($available=true, $price=9900));
        ClientFactory::shouldReceive('make')->andReturn(new FakeClient);


        $stock = tap(Stock::first())->track();

        $this->assertTrue($stock->in_stock);
        $this->assertEquals(9900, $stock->price);
    }
}

class FakeClient implements Client{

    public function checkAvailability(Stock $stock): StockStatus
    {
        return new StockStatus($available=true, $price=9900);
    }
}
