<?php

namespace App\Models;

use App\Clients\BestBuy;
use App\Clients\ClientException;
use Facades\App\Clients\ClientFactory;
use App\Clients\Target;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Stock extends Model {

    use HasFactory;

    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        // hit an API endpoint for the associated retailer
        // Fetch the up-to-date details for the item
        $status = $this->retailer->client()->checkAvailability($this);


        // And then refresh the current stock record
        $this->update([
            'in_stock' => $status->available,
            'price' => $status->price
        ]);

    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}
