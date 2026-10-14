<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'price',
        'stock',
        'minimum_stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
    ];

    /**
     * Relationship: Produk dimiliki oleh satu Kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relationship: Produk memiliki banyak riwayat transaksi pergerakan stok.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'product_id')->latest();
    }

    /**
     * Cek apakah stok produk berada pada atau di bawah batas minimum (Business Rule P4).
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }

    /**
     * Format harga Rupiah untuk kemudahan tampilan Blade.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }
}
