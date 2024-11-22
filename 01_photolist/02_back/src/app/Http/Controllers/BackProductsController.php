<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Supplier;

use App\Models\Product;


class BackProductsController extends Controller
{

    public function index(Request $request)
    {

        // $products = Product::
        //     whereIn('商品番号', 
        //     [
        //      '100-35-1',
        //      '104750-16', 
        //      '104750-18',
        //      '104750-17',
        //      ])
        //     //  ->where('商品名', 'like', '%子供ランドセル用ナイロンレインコート%')
        //      ->where('商品バリ.SKU発注番号', '3100')
        //      ->with(['supplier', 'product_vari', 'product_net', 'code'])->get();


        $products = Product::query()
        ->join('商品バリ', '商品.商品番号', '=', '商品バリ.商品番号')
        ->where('商品バリ.SKU発注番号', '3100')
        ->select('商品.商品番号', '商品.商品名', '商品.仕入先', '商品バリ.サイズ', '商品バリ.カラー') // Select necessary columns
        ->groupBy('商品.商品番号', '商品.商品名', '商品.仕入先', '商品バリ.サイズ', '商品バリ.カラー') // Group by necessary columns
        ->get();


/*
検索対象は「品番」「発注番号」「商品名」「メーカー」「サイズ」「カラー」としたい
SQLが重い場合はラジオボタンで分ける
空白で区切った場合、AND検索かOR検索かをラジオボタンで指定可能とする（既定値はOR）

品番：TM_商品.商品番号
発注番号：TM_商品バリ.SKU発注番号
商品名：TM_商品.商品名
メーカー：TM_商品.仕入先（仕入先名）
サイズ：TM_商品バリ.サイズ
カラー：TM_商品バリ.カラー


*/

        dd($products);
        dd($products->first()->firstProductVari());

        return new JsonResponse([
            'data' => $products,
        ]);
        // return response()->json($supplier);
    }
    
}
