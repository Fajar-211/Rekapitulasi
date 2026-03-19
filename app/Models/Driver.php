<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory;
    protected $fillable = ['nama', 'telp'];
    
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'driver_id');
    }
}
