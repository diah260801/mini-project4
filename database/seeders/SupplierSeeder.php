<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT Mega Solusi Informatika',
                'phone' => '021-5551234',
                'email' => 'sales@megasolusi.co.id',
                'address' => 'Jl. Jenderal Sudirman No. 45, Jakarta Selatan',
            ],
            [
                'name' => 'CV Sumber Makmur Stationery',
                'phone' => '022-7778899',
                'email' => 'contact@sumbermakmur.id',
                'address' => 'Jl. Asia Afrika No. 102, Bandung',
            ],
            [
                'name' => 'PT Mitra Distribusi Nusantara',
                'phone' => '031-8889900',
                'email' => 'order@mitradistribusi.com',
                'address' => 'Jl. Raya Darmo No. 88, Surabaya',
            ],
            [
                'name' => 'CV Sentral Jaya Mandiri',
                'phone' => '024-3334455',
                'email' => 'sentraljaya@gmail.com',
                'address' => 'Jl. Pemuda No. 12, Semarang',
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::firstOrCreate(['name' => $sup['name']], $sup);
        }
    }
}
