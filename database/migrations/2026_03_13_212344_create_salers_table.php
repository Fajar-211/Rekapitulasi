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
        Schema::create('salers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained(
                table: 'customers',
                indexName: 'salers_customer_id'
            );
            $table->foreignId('status_id')->constrained(
                table: 'statuses',
                indexName: 'salers_status_id'
            );
            $table->decimal('tonase', 15, 2);
            $table->decimal('tonase_gp', 15, 2)->nullable();
            $table->decimal('harga', 15, 2);
            $table->decimal('harga_gp', 15, 2)->nullable();
            $table->decimal('kasbon', 15, 2)->nullable();
            $table->decimal('bongkar', 15, 2)->nullable();
            $table->decimal('mati', 15, 2);
            $table->decimal('jumlah', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salers');
    }
};
