<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingTransaction extends Model
{
    protected $fillable = [
        'family_group_id',
        'assigned_to_user_id',
        'creator_user_id',
        'description',
        'amount',
        'due_date',
        'status',
        'transaction_id'
    ];

    public function familyGroup()
    {
        return $this->belongsTo(FamilyGroup::class);
    }

    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function creatorUser()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
