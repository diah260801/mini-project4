<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
