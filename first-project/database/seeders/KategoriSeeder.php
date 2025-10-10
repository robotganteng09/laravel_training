<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['nama' => 'Elektronik']);
        Category::create(['nama' => 'Pakaian']);
        Category::create(['nama' => 'Makanan']);
    }
}
