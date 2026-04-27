<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeOperasional extends Model
{
    protected $fillable = ['type'];

    public function operasional(): HasMany
    {
        return $this->hasMany(Operasional::class, 'type_id');
    }
}
