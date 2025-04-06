<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Untuk generate UUID

class StokOpname extends Model
{
    use HasFactory;
    
    protected $table = 'stok_opname';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_stok',
        'jenis_perubahan',
        'jumlah_perubahan',
        'satuan',
    ];

    public function stok()
    {
        return $this->belongsTo(StokProduk::class, 'id_stok');
    }
}
