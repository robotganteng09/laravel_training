<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'gambar',
        'judul',
        'deskripsi',
        'harga',
        'stok',
        'category_id',
    ];

    protected function gambar(): Attribute
    {
        return Attribute::make(
            get: fn($gambar) => url('/storage/products', $gambar),
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cart(){
        return $this->hasMany(CartModel::class);
    }
}
