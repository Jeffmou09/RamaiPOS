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
        'id', 'nama_produk', 'stok', 'harga_beli', 'harga_jual'
    ];
    
    public function stok()
    {
        return $this->hasOne(StokProduk::class, 'id_produk');
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->id) {
                // Get the latest product ID
                $lastProduct = self::orderBy('id', 'desc')->first();
                
                if ($lastProduct) {
                    // Extract the numeric part and increment it
                    $numericPart = intval(substr($lastProduct->id, 1));
                    $nextNumericPart = $numericPart + 1;
                } else {
                    // If no products exist yet, start with 1
                    $nextNumericPart = 1;
                }
                
                // Format to P000001 style
                $model->id = 'P' . str_pad($nextNumericPart, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}