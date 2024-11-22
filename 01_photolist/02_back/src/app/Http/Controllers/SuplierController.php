<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Supplier;

use App\Models\Product;


class SuplierController extends Controller
{

    public function index(Request $request)
    {
        // データ取得
        $supplier = Supplier::get();

        $supplier = Product::whereIn('商品番号', 
            ['104750-16', 
             '104750-18',
             '104750-17',
            //  ])->with(['supplier', 'product_vari', 'product_net', 'code'])->get();
             ])->with(['supplier', 'product_vari', 'product_net'])->get();

        dd($supplier);
        return new JsonResponse([
            'data' => $supplier,
        ]);
        // return response()->json($supplier);
    }
    
}
