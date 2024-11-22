<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Supplier;

use App\Models\Product;


class MakerController extends Controller
{

    public function index(Request $request)
    {
        // データ取得
        $makers = DB::table('TM_仕入先')->orderBy('モール表示順')->get();

        // 属性カラムに「定番」の表示がある場合、メーカー名の前に【定】を付与
        if ($makers->isEmpty()) {
            return new JsonResponse([
                'data' => [],
            ]);

        } else {
            foreach ($makers as $maker) {
                if ($maker->属性 == '定番') {
                    $maker->メーカー名 = '【定】 ' . $maker->メーカー名;
                }
            }
            return new JsonResponse([
                'data' => $makers,
            ]);
        }

    }
    
}
