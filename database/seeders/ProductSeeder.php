<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catElektronik = Category::where('name', 'Elektronik & Gadget')->first();
        $catAksesoris = Category::where('name', 'Aksesoris Komputer')->first();
        $catAtk = Category::where('name', 'Peralatan Kantor (ATK)')->first();
        $catFurniture = Category::where('name', 'Furniture Kantor')->first();
        $catJaringan = Category::where('name', 'Jaringan & Networking')->first();

        $products = [
            // Produk Elektronik
            [
                'category_id' => $catElektronik->id,
                'sku' => 'PRD-ELK-001',
                'name' => 'Laptop Lenovo ThinkPad T14 Gen 3',
                'price' => 14500000.00,
                'stock' => 8,
                'minimum_stock' => 3,
            ],
            [
                'category_id' => $catElektronik->id,
                'sku' => 'PRD-ELK-002',
                'name' => 'Monitor Dell UltraSharp 24 Inch IPS',
                'price' => 3250000.00,
                'stock' => 2, // Stok menipis!
                'minimum_stock' => 4,
            ],
            [
                'category_id' => $catElektronik->id,
                'sku' => 'PRD-ELK-003',
                'name' => 'Printer Epson EcoTank L3210 All-in-One',
                'price' => 2400000.00,
                'stock' => 5,
                'minimum_stock' => 2,
            ],

            // Aksesoris Komputer
            [
                'category_id' => $catAksesoris->id,
                'sku' => 'PRD-AKS-001',
                'name' => 'Logitech MX Master 3S Wireless Mouse',
                'price' => 1450000.00,
                'stock' => 12,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $catAksesoris->id,
                'sku' => 'PRD-AKS-002',
                'name' => 'Mechanical Keyboard Keychron K2 Wireless',
                'price' => 1250000.00,
                'stock' => 3, // Stok menipis!
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $catAksesoris->id,
                'sku' => 'PRD-AKS-003',
                'name' => 'Webcam Full HD 1080p with Noise Cancelling Mic',
                'price' => 450000.00,
                'stock' => 15,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $catAksesoris->id,
                'sku' => 'PRD-AKS-004',
                'name' => 'Headset USB Jabra Evolve 20 Mono',
                'price' => 550000.00,
                'stock' => 1, // Stok menipis!
                'minimum_stock' => 3,
            ],

            // ATK
            [
                'category_id' => $catAtk->id,
                'sku' => 'PRD-ATK-001',
                'name' => 'Kertas HVS PaperOne A4 80 GSM (1 Rim)',
                'price' => 55000.00,
                'stock' => 45,
                'minimum_stock' => 10,
            ],
            [
                'category_id' => $catAtk->id,
                'sku' => 'PRD-ATK-002',
                'name' => 'Pulpen Gel Pilot G2 0.5mm Hitam (1 Lusin)',
                'price' => 165000.00,
                'stock' => 20,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $catAtk->id,
                'sku' => 'PRD-ATK-003',
                'name' => 'Tinta Botol Epson 003 Black Original',
                'price' => 85000.00,
                'stock' => 4, // Stok menipis!
                'minimum_stock' => 6,
            ],

            // Furniture
            [
                'category_id' => $catFurniture->id,
                'sku' => 'PRD-FUR-001',
                'name' => 'Kursi Kerja Ergonomis Mesh Fantech OCA258',
                'price' => 950000.00,
                'stock' => 7,
                'minimum_stock' => 2,
            ],
            [
                'category_id' => $catFurniture->id,
                'sku' => 'PRD-FUR-002',
                'name' => 'Meja Kerja Minimalis 120x60cm Kaki Besi',
                'price' => 850000.00,
                'stock' => 2, // Stok menipis!
                'minimum_stock' => 2,
            ],

            // Jaringan
            [
                'category_id' => $catJaringan->id,
                'sku' => 'PRD-JAR-001',
                'name' => 'Router WiFi 6 TP-Link Archer AX12 Gigabit',
                'price' => 580000.00,
                'stock' => 10,
                'minimum_stock' => 3,
            ],
            [
                'category_id' => $catJaringan->id,
                'sku' => 'PRD-JAR-002',
                'name' => 'Kabel LAN UTP Belden Cat6 305 Meter',
                'price' => 1850000.00,
                'stock' => 3,
                'minimum_stock' => 2,
            ],
        ];

        foreach ($products as $prod) {
            Product::firstOrCreate(['sku' => $prod['sku']], $prod);
        }
    }
}
