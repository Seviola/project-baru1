<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'user_id',
        'address'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}