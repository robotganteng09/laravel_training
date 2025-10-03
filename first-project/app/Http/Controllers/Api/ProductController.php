<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\CategoryServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // get data
    public function index()
    {
        $products = Product::latest()->paginate(5);
        return new ProductResource(true, 'List Data Products', $products);
    }


    protected $CategoryServices;

    public function __construct(CategoryServices $CategoryServices)
    {
        $this->CategoryServices = $CategoryServices;
    }

    //add data
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'judul' => 'required',
    //         'deskripsi' => 'required',
    //         'harga' => 'required|numeric',
    //         'stok' => 'required|numeric',
    //         'category_id' => 'nullable|exists:category,id',
    //         'category_name' => 'nullable|string|max:45',

    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }

    //     $categoryId = $request->category_id;
    //     if (!$categoryId && $request->filled('category_name')) {
    //         $category = $this->CategoryServices->findOrCreate($request->category_name);
    //         $categoryId = $category->id;
    //     }

    //     if (!$categoryId) {
    //         return response()->json([
    //             'eror' => 'Kategori harus dipilih atau dibuat baru'
    //         ], 422);
    //     }

    //upload image
    //     $gambar = $request->file('gambar');
    //     $namaFile = $gambar->hashName();
    //     $gambar->storeAs('products', $namaFile);

    //     //create product
    //     $product = Product::create([
    //         'gambar' => $namaFile,
    //         'judul' => $request->judul,
    //         'deskripsi' => $request->deskripsi,
    //         'harga' => $request->harga,
    //         'stok' => $request->stok,
    //         'category_id' => $categoryId,

    //     ]);

    //     return new ProductResource(true, 'Data berhasil ditambahkan!', $product);
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'judul' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'category_id' => 'nullable|exists:category,id',
            'category_name' => 'nullable|string|max:45',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $categoryId = $request->category_id;
        if (!$categoryId && $request->filled('category_name')) {
            $category = $this->CategoryServices->findOrCreate($request->category_name);
            $categoryId = $category->id;
        }

        if (!$categoryId) {
            return response()->json([
                'eror' => 'Kategoru harus dipilih atau dibuat baru'
            ], 422);
        }
        //upload image
        $gambar = $request->file('gambar');
        $namaFile = $gambar->hashName();
        $gambar->storeAs('products', $namaFile);
        //create product
        $product = Product::create([
            'gambar' => $namaFile,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'category_id' => $categoryId,
        ]);
        return new ProductResource(true, 'Data berhasil ditambahkan!', $product);
    }

    public function show($id)
    {
        $product = Product::find($id);
        return new ProductResource(true, 'Detail data product', $product);
    }

    public function update(Request $request, $id) //update product
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'judul' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        };
        $product = Product::find($id); //get product by id

        if ($request->hasFile('gambar')) {
            Storage::delete('products/' . basename($product->gambar)); //delete old image
            $image = $request->file('gambar');
            $image->storeAs('products', $image->hashName());

            $product->update([
                'gambar' => $image->hashName(),
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'stok' => $request->stok,
            ]);
        } else {
            $product->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'stok' => $request->stok,
            ]);
        }
        return new ProductResource(true, 'data product berhasil diubah', $product);
    }

    public function destroy($id)
    { //hapus product
        $product = Product::find($id);
        Storage::delete('products/' . basename($product->image));
        $product->delete();
        return new ProductResource(true, 'data product berhasil dihapus', null);
    }
}
