<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    /** @use HasFactory<\Database\Factories\StatusFactory> */
    use HasFactory;
    protected $fillable = ['status'];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'status_id');
    }
    public function sales(): HasMany
    {
        return $this->hasMany(Saler::class, 'status_id');
    }
}
