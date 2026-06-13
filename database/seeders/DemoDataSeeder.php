<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Kategori ──────────────────────────────────────────────────────
        $categories = [
            ['name' => 'Nasi & Makanan Berat',     'description' => 'Nasi kucing, nasi bungkus, dan hidangan berbahan dasar nasi'],
            ['name' => 'Gorengan & Kudapan',        'description' => 'Aneka gorengan dan jajanan pasar khas angkringan'],
            ['name' => 'Sate & Bakar',              'description' => 'Sate dan hidangan yang dibakar di atas bara'],
            ['name' => 'Minuman Panas',             'description' => 'Teh, kopi, susu, dan minuman panas lainnya'],
            ['name' => 'Minuman Dingin & Es',       'description' => 'Es teh, es jeruk, es campur, dan minuman dingin lainnya'],
            ['name' => 'Mie & Bakso',               'description' => 'Mie rebus, mie goreng, bakso, dan variasinya'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[$cat['name']] = Category::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description']]
            );
        }

        // ── Produk ────────────────────────────────────────────────────────
        $products = [
            // --- Nasi & Makanan Berat ---
            [
                'category'      => 'Nasi & Makanan Berat',
                'name'          => 'Nasi Kucing Ayam',
                'selling_price' => 4000,
                'capital_price' => 2000,
                'stock'         => 40,
                'minimum_stock' => 10,
            ],
            [
                'category'      => 'Nasi & Makanan Berat',
                'name'          => 'Nasi Kucing Ikan Asin',
                'selling_price' => 3000,
                'capital_price' => 1500,
                'stock'         => 40,
                'minimum_stock' => 10,
            ],
            [
                'category'      => 'Nasi & Makanan Berat',
                'name'          => 'Nasi Kucing Tempe Orek',
                'selling_price' => 3000,
                'capital_price' => 1500,
                'stock'         => 35,
                'minimum_stock' => 10,
            ],
            [
                'category'      => 'Nasi & Makanan Berat',
                'name'          => 'Nasi Bungkus Ayam Goreng',
                'selling_price' => 12000,
                'capital_price' => 7000,
                'stock'         => 20,
                'minimum_stock' => 5,
            ],
            [
                'category'      => 'Nasi & Makanan Berat',
                'name'          => 'Nasi Bungkus Telur Kecap',
                'selling_price' => 10000,
                'capital_price' => 6000,
                'stock'         => 20,
                'minimum_stock' => 5,
            ],

            // --- Gorengan & Kudapan ---
            [
                'category'      => 'Gorengan & Kudapan',
                'name'          => 'Tempe Goreng',
                'selling_price' => 1500,
                'capital_price' => 700,
                'stock'         => 60,
                'minimum_stock' => 15,
            ],
            [
                'category'      => 'Gorengan & Kudapan',
                'name'          => 'Tahu Goreng',
                'selling_price' => 1500,
                'capital_price' => 700,
                'stock'         => 60,
                'minimum_stock' => 15,
            ],
            [
                'category'      => 'Gorengan & Kudapan',
                'name'          => 'Bakwan Sayur',
                'selling_price' => 2000,
                'capital_price' => 900,
                'stock'         => 50,
                'minimum_stock' => 10,
            ],
            [
                'category'      => 'Gorengan & Kudapan',
                'name'          => 'Risoles Mayo',
                'selling_price' => 3000,
                'capital_price' => 1500,
                'stock'         => 30,
                'minimum_stock' => 8,
            ],
            [
                'category'      => 'Gorengan & Kudapan',
                'name'          => 'Pisang Goreng Crispy',
                'selling_price' => 2500,
                'capital_price' => 1200,
                'stock'         => 30,
                'minimum_stock' => 8,
            ],
            [
                'category'      => 'Gorengan & Kudapan',
                'name'          => 'Ote-Ote / Bakwan Udang',
                'selling_price' => 2000,
                'capital_price' => 1000,
                'stock'         => 40,
                'minimum_stock' => 10,
            ],

            // --- Sate & Bakar ---
            [
                'category'      => 'Sate & Bakar',
                'name'          => 'Sate Usus Ayam',
                'selling_price' => 3000,
                'capital_price' => 1500,
                'stock'         => 50,
                'minimum_stock' => 10,
            ],
            [
                'category'      => 'Sate & Bakar',
                'name'          => 'Sate Kulit Ayam',
                'selling_price' => 3000,
                'capital_price' => 1500,
                'stock'         => 50,
                'minimum_stock' => 10,
            ],
            [
                'category'      => 'Sate & Bakar',
                'name'          => 'Sate Telur Puyuh',
                'selling_price' => 2500,
                'capital_price' => 1200,
                'stock'         => 40,
                'minimum_stock' => 10,
            ],
            [
                'category'      => 'Sate & Bakar',
                'name'          => 'Sate Kerang',
                'selling_price' => 4000,
                'capital_price' => 2000,
                'stock'         => 30,
                'minimum_stock' => 8,
            ],
            [
                'category'      => 'Sate & Bakar',
                'name'          => 'Bacem Tempe Bakar',
                'selling_price' => 3000,
                'capital_price' => 1500,
                'stock'         => 25,
                'minimum_stock' => 5,
            ],

            // --- Minuman Panas ---
            [
                'category'      => 'Minuman Panas',
                'name'          => 'Teh Panas',
                'selling_price' => 3000,
                'capital_price' => 1000,
                'stock'         => 100,
                'minimum_stock' => 20,
            ],
            [
                'category'      => 'Minuman Panas',
                'name'          => 'Teh Manis Panas',
                'selling_price' => 4000,
                'capital_price' => 1200,
                'stock'         => 100,
                'minimum_stock' => 20,
            ],
            [
                'category'      => 'Minuman Panas',
                'name'          => 'Kopi Hitam',
                'selling_price' => 4000,
                'capital_price' => 1500,
                'stock'         => 80,
                'minimum_stock' => 15,
            ],
            [
                'category'      => 'Minuman Panas',
                'name'          => 'Kopi Susu',
                'selling_price' => 6000,
                'capital_price' => 2500,
                'stock'         => 80,
                'minimum_stock' => 15,
            ],
            [
                'category'      => 'Minuman Panas',
                'name'          => 'Wedang Jahe',
                'selling_price' => 5000,
                'capital_price' => 2000,
                'stock'         => 50,
                'minimum_stock' => 10,
            ],
            [
                'category'      => 'Minuman Panas',
                'name'          => 'Susu Panas',
                'selling_price' => 5000,
                'capital_price' => 2000,
                'stock'         => 60,
                'minimum_stock' => 10,
            ],

            // --- Minuman Dingin & Es ---
            [
                'category'      => 'Minuman Dingin & Es',
                'name'          => 'Es Teh Manis',
                'selling_price' => 5000,
                'capital_price' => 2000,
                'stock'         => 80,
                'minimum_stock' => 15,
            ],
            [
                'category'      => 'Minuman Dingin & Es',
                'name'          => 'Es Jeruk',
                'selling_price' => 6000,
                'capital_price' => 2500,
                'stock'         => 60,
                'minimum_stock' => 10,
            ],
            [
                'category'      => 'Minuman Dingin & Es',
                'name'          => 'Es Kopi Susu',
                'selling_price' => 8000,
                'capital_price' => 3500,
                'stock'         => 50,
                'minimum_stock' => 10,
            ],

            // --- Mie & Bakso ---
            [
                'category'      => 'Mie & Bakso',
                'name'          => 'Mie Rebus Telur',
                'selling_price' => 8000,
                'capital_price' => 4000,
                'stock'         => 25,
                'minimum_stock' => 5,
            ],
            [
                'category'      => 'Mie & Bakso',
                'name'          => 'Mie Goreng Telur',
                'selling_price' => 9000,
                'capital_price' => 4500,
                'stock'         => 25,
                'minimum_stock' => 5,
            ],
            [
                'category'      => 'Mie & Bakso',
                'name'          => 'Bakso Kuah',
                'selling_price' => 10000,
                'capital_price' => 5000,
                'stock'         => 20,
                'minimum_stock' => 5,
            ],
        ];

        foreach ($products as $p) {
            $category = $createdCategories[$p['category']] ?? null;
            if (! $category) continue;

            Product::firstOrCreate(
                [
                    'name'        => $p['name'],
                    'category_id' => $category->id,
                ],
                [
                    'selling_price' => $p['selling_price'],
                    'capital_price' => $p['capital_price'],
                    'stock'         => $p['stock'],
                    'minimum_stock' => $p['minimum_stock'],
                    'status'        => 'active',
                ]
            );
        }

        $this->command->info('Demo data seeded: ' . count($categories) . ' kategori, ' . count($products) . ' produk.');
    }
}
