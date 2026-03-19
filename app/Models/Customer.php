<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['nama_depan', 'nama_belakang'];

    public function sales(): HasMany
    {
        return $this->hasMany(Saler::class, 'customer_id');
    }
}
