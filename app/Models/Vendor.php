<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
    'name',
    'phone',
    'email',
    'address'
    ];
    
    public function products()
    {
    return $this->hasMany(Product::class);
    }
}
