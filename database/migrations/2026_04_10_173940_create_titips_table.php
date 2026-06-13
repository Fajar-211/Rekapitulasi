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
        Schema::create('titips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saler_id')->constrained(
                table: 'salers',
                indexName: 'titip_saler_id'
            );
            $table->date('tanggal')->nullable();
            $table->decimal('nominal', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titips');
    }
};
