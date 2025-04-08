<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id';
    public $incrementing = false; // Karena ID adalah UUID (char 36)
    protected $keyType = 'string';
    
    protected $fillable = [
        'id', 
        'id_customer', 
        'tanggal_transaksi', 
        'diskon', 
        'jumlah_produk_terjual', 
        'total_transaksi',
        'deskripsi'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id');
    }
}