<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $suppliers = Supplier::all();

        if ($products->isEmpty() || $suppliers->isEmpty()) {
            return;
        }

        $laptop = Product::where('sku', 'PRD-ELK-001')->first();
        $mouse = Product::where('sku', 'PRD-AKS-001')->first();
        $kertas = Product::where('sku', 'PRD-ATK-001')->first();
        $supTekno = Supplier::where('name', 'PT Mega Solusi Informatika')->first();
        $supAtk = Supplier::where('name', 'CV Sumber Makmur Stationery')->first();

        $movements = [
            [
                'product_id' => $laptop->id,
                'supplier_id' => $supTekno->id,
                'type' => 'in',
                'quantity' => 10,
                'note' => 'Pengadaan unit awal semester',
                'created_at' => now()->subDays(5),
            ],
            [
                'product_id' => $laptop->id,
                'supplier_id' => null,
                'type' => 'out',
                'quantity' => 2,
                'note' => 'Distribusi unit ke divisi Marketing',
                'created_at' => now()->subDays(2),
            ],
            [
                'product_id' => $mouse->id,
                'supplier_id' => $supTekno->id,
                'type' => 'in',
                'quantity' => 15,
                'note' => 'Restock aksesoris mouse',
                'created_at' => now()->subDays(4),
            ],
            [
                'product_id' => $mouse->id,
                'supplier_id' => null,
                'type' => 'out',
                'quantity' => 3,
                'note' => 'Penjualan ke divisi operasional',
                'created_at' => now()->subDay(),
            ],
            [
                'product_id' => $kertas->id,
                'supplier_id' => $supAtk->id,
                'type' => 'in',
                'quantity' => 50,
                'note' => 'Restock bulanan ATK kantor',
                'created_at' => now()->subDays(3),
            ],
            [
                'product_id' => $kertas->id,
                'supplier_id' => null,
                'type' => 'out',
                'quantity' => 5,
                'note' => 'Pemakaian divisi Keuangan',
                'created_at' => now()->subHours(6),
            ],
        ];

        foreach ($movements as $m) {
            StockMovement::create($m);
        }
    }
}
