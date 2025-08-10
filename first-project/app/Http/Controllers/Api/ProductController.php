<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // get data
    public function index(){
        $products = Product::latest()->paginate(5);
        return new ProductResource(true,'List Data Products',$products);
        
    }

    //add data
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
           'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           'judul' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric'

            
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);

        }
        //upload image
        $gambar = $request->file('gambar');
        $namaFile = $gambar->hashName();
        $gambar->storeAs('products',$namaFile);

        //create product
        $product = Product::create([
            'gambar' => $namaFile,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return new ProductResource(true,'Data berhasil ditambahkan!',$product);

       
    }
     public function show($id){
            $product = Product::find($id);
            return new ProductResource(true,'Detail data product',$product);
        }
}
