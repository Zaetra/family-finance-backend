<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\FamilyGroup;
use App\Models\Account;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'family_group_id',
        'account_id',
        'amount',
        'type',
        'category',
        'description',
        'transaction_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function familyGroup()
    {
        return $this->belongsTo(FamilyGroup::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
