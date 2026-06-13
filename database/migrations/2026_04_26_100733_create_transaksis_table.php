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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('transaksi')->unique();
            $table->foreignId('purchase_id')->constrained(
                table: 'purchases',
                indexName: 'transaksis_purchase_id'
            );
            $table->foreignId('saler_id')->constrained(
                table: 'salers',
                indexName: 'transaksis_saler_id'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
