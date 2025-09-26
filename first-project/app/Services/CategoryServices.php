<?php

namespace App\Services;

use App\Http\Controllers\Api\CategoryController;
use App\Models\Category;
use Illuminate\Validation\Rules\Can;

class CategoryServices
{
    public function findOrCreate(string $name): Category
    {
        $category = Category::whereRaw('LOWER(nama) = ?', [strtolower($name)])->first();
        if (!$category) {
            $category = Category::create(['nama' => ucfirst(strtolower($name))]);
        }
        return $category;
    }
}
