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
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->id) {
                // Get the latest customer ID
                $lastCustomer = self::orderBy('id', 'desc')->first();
                
                if ($lastCustomer) {
                    // Extract the numeric part and increment it
                    $numericPart = intval(substr($lastCustomer->id, 1));
                    $nextNumericPart = $numericPart + 1;
                } else {
                    // If no customers exist yet, start with 1
                    $nextNumericPart = 1;
                }
                
                // Format to C000001 style
                $model->id = 'C' . str_pad($nextNumericPart, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}