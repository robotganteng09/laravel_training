<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryServices;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'message' => 'List kategori',
            'data' => $categories
        ], 200);
    }
    protected $CategoryServices;

    public function __construct(CategoryServices $CategoryServices)
    {
        $this-> CategoryServices = $CategoryServices;
    }

    public function store(Request $request){
       $validator = FacadesValidator::make($request->all(), [
        'nama' => 'required|string|max:45'
       ]);

       if($validator->fails()){
        return response()->json($validator->errors(),422);
       }

       $category = $this->CategoryServices->findOrCreate($request->nama);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dibuat atau sudah ada',
            'data'    => $category
        ], 201);
    }
    
    public function destroy($id){
     $category = Category::find($id);
     if(!$category){
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan',
            ], 404);
     }
     $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus',
        ], 200);
    }
}
