<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'nama_customer', 'alamat', 'no_hp'];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_customer', 'id');
    }
}
