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
        Schema::create('stok_opname', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('id_produk', 36);
            $table->string('jenis_perubahan');
            $table->integer('jumlah_perubahan');
            $table->timestamps();

            $table->foreign('id_produk')->references('id')->on('produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_opname');
    }
};
