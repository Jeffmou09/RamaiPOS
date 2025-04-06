<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokProduk extends Model
{
    use HasFactory;

    protected $table = 'stok_produk';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'char';
    protected $fillable = [
        'id',
        'id_produk',
        'stok_satuan_utama',
        'stok_satuan_isi'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function stokOpname()
    {
        return $this->hasMany(StokOpname::class, 'id_stok');
    }
}
