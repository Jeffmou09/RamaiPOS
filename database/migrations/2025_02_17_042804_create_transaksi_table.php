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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('id_customer', 36)->nullable();
            $table->date('tanggal_transaksi');
            $table->integer('diskon')->nullable();
            $table->integer('jumlah_produk_terjual');
            $table->integer('total_transaksi');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
    
            $table->foreign('id_customer')->references('id')->on('customer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
