<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik & Gadget',
                'description' => 'Perangkat elektronik, laptop, smartphone, dan perlengkapan digital.',
            ],
            [
                'name' => 'Aksesoris Komputer',
                'description' => 'Mouse, keyboard, webcam, headset, dan peripheral komputer lainnya.',
            ],
            [
                'name' => 'Peralatan Kantor (ATK)',
                'description' => 'Kertas HVS, pulpen, binder, map, dan alat tulis kantor.',
            ],
            [
                'name' => 'Furniture Kantor',
                'description' => 'Meja kerja, kursi ergonomis, lemari berkas, dan partisi.',
            ],
            [
                'name' => 'Jaringan & Networking',
                'description' => 'Router, switch, kabel LAN UTP, access point, dan crimping tool.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
