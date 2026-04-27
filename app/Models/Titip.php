<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Titip extends Model
{
    protected $fillable = ['saler_id', 'nomor', 'nominal'];

    public function saler(): BelongsTo
    {
        return $this->belongsTo(Saler::class, 'saler_id');
    }
}
