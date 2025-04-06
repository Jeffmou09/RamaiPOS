<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'nama_produk', 'satuan', 'isi_per_satuan', 'jenis_isi',
        'harga_beli_per_satuan', 'harga_beli_per_isi',
        'harga_jual_per_satuan', 'harga_jual_per_isi'
    ];

    public function stok()
    {
        return $this->hasOne(StokProduk::class, 'id_produk');
    }
}
