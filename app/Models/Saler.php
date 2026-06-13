<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Saler extends Model
{
    /** @use HasFactory<\Database\Factories\SalerFactory> */
    use HasFactory;
    protected $fillable = ['customer_id', 'tonase', 'tonase_gp', 'harga', 'harga_gp', 'kasbon', 'bongkar', 'mati', 'piutang', 'jumlah'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function titip(): HasMany
    {
        return $this->hasMany(Titip::class, 'saler_id');
    }
    public function transaksi(): HasOne
    {
        return $this->hasOne(Transaksi::class, 'saler_id');
    }
}
