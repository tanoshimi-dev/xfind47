<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Supplier;

class PrefectureController extends Controller
{

    public function search(Request $request)
    {
        // データ取得
        $prefectures = DB::table('prefectures')->orderBy('id', 'asc')->get();
        return new JsonResponse([
            'data' => $prefectures,
        ]);
    }
    
}
