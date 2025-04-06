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
        Schema::create('produk', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('nama_produk');
            $table->string('satuan');
            $table->integer('isi_per_satuan');
            $table->string('jenis_isi');
            $table->integer('harga_beli_per_satuan');
            $table->integer('harga_beli_per_isi');
            $table->integer('harga_jual_per_satuan');
            $table->integer('harga_jual_per_isi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
