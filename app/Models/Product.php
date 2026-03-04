<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

class Product extends Model
{
    protected $fillable =[
        'barcode',
        'name',
        'purchase_price',
        'price',
        'stock',
        'description',
        'image',
        'vendor_id'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
