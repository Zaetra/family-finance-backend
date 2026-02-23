<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionShare extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'amount'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
