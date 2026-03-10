<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    protected $fillable = [
    'vendor_id',
    'name',
    'purchase_price',
    'description'
    ];
    
    public function vendor()
    {
    return $this->belongsTo(Vendor::class);
    }
}
