<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
    ];

    /**
     * Relationship: Supplier memiliki banyak riwayat transaksi pergerakan stok.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'supplier_id');
    }
}
