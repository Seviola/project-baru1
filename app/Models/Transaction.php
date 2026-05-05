<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // protected $fillable = ['total_price', 'paid_amount', 'change_amount'];
    protected $fillable = [
        'invoice',
        'student_name',
        'payment_method',
        'total',
        'pay',
        'change',
        'user_id',
        'is_deposited'
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}