<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;

class Target implements Client {

    public function checkAvailability(Stock $stock) :StockStatus
    {
        $result = Http::get('http://foo.test')->json();

        return new StockStatus($result['available'], $result['price']);
    }
}
