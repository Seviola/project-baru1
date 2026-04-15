<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    protected $fillable = [
        'batch_id', 
        'vendor_id',
        'product_id',
        'stock',
        'status',
        'approved_stock',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}