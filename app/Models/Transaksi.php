<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $fillable = ['transaksi', 'purchase_id', 'saler_id'];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
    public function saler(): BelongsTo
    {
        return $this->belongsTo(Saler::class, 'saler_id');
    }
}
