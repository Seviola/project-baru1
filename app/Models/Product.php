<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];
}
