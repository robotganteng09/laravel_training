<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elektronik = Category::where('nama', 'Elektronik')->first();
        $pakaian = Category::where('nama', 'Pakaian')->first();
        Product::create([
            'gambar' => 'tv.jpg',
            'judul' => 'Televisi LED 32 Inch',
            'deskripsi' => 'Televisi dengan kualitas HD, cocok untuk hiburan keluarga.',
            'harga' => 2500000,
            'stok' => 10,
            'category_id' => $elektronik->id,
        ]);
        Product::create([
            'gambar' => 'baju.jpg',
            'judul' => 'Kaos Polos',
            'deskripsi' => 'Kaos polos nyaman dipakai sehari-hari.',
            'harga' => 75000,
            'stok' => 50,
            'category_id' => $pakaian->id,
        ]);
    }
}
