<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use SebastianBergmann\CodeCoverage\Test\Target\Method;

class Purchase extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseFactory> */
    use HasFactory;
    protected $fillable = ['vendor_id', 'driver_id', 'tonase', 'harga', 'methode_id', 'status_id', 'jumlah', 'size', 'tanggal', 'transaksi', 'mati', 'kompensasi'];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function methode(): BelongsTo
    {
        return $this->belongsTo(MethodePembayaran::class, 'methode_id');
    }
    public function transaksi(): HasOne
    {
        return $this->hasOne(Transaksi::class, 'purchase_id');
    }
}
