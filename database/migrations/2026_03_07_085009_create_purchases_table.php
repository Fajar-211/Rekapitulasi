<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained(
                table: 'vendors',
                indexName: 'purchases_vendor_id'
            );
            $table->foreignId('driver_id')->constrained(
                table: 'drivers',
                indexName: 'purchases_driver_id'
            );
            $table->foreignId('methode_id')->constrained(
                table: 'methode_pembayarans',
                indexName: 'purchases_methode_pembayaran_id'
            );
            $table->foreignId('status_id')->constrained(
                table: 'statuses',
                indexName: 'purchases_status_id'
            );
            $table->decimal('tonase');
            $table->decimal('harga');
            $table->decimal('size');
            $table->date('tanggal');
            $table->decimal('jumlah', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
