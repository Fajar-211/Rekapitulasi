<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Operasional extends Model
{
    protected $fillable = ['type_id', 'jumlah'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeOperasional::class, 'type_id');
    }
}
