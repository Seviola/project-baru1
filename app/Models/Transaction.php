<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // protected $fillable = ['total_price', 'paid_amount', 'change_amount'];
    protected $fillable = ['invoice','total','pay','change'];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}