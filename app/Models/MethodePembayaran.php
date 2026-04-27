<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MethodePembayaran extends Model
{
    protected $fillable = ['methode'];

    public function purchase(): HasMany
    {
        return $this->hasMany(Purchase::class, 'methode_id');
    }
}
