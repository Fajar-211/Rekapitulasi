<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saler extends Model
{
    /** @use HasFactory<\Database\Factories\SalerFactory> */
    use HasFactory;
    protected $fillable = ['customer_id', 'status_id', 'tonase', 'tonase_gp', 'harga', 'harga_gp', 'kasbon', 'bongkar', 'mati', 'jumlah'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
