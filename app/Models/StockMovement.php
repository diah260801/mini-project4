<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';

    protected $fillable = [
        'product_id',
        'supplier_id',
        'type',
        'quantity',
        'note',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Relationship: Transaksi pergerakan stok milik satu Produk.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relationship: Transaksi pergerakan stok terhubung ke Supplier (nullable).
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
