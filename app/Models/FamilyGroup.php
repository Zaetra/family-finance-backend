<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyGroup extends Model
{
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
