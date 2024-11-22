<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Supplier;

use App\Models\Product;


class PhotoListController extends Controller
{

    public function search(Request $request)
    {

        $limit = 30;
        $per_page = 30;
        $page = $request->get('page', 1);

        $sort_column = "";
        $sort_direction = "";
        
        // 画面から指定されたソートキーを取得
        $sort_key =  $request->get('sort', '1');
        // switch ($sort_key) {
        //     case '0':
        //         $sort_column = 'TM_商品.登録日時';
        //         $sort_direction = 'asc';
        //         break;
        //     case '1':
        //         $sort_column = 'TM_商品.登録日時';
        //         $sort_direction = 'desc';
        //         break;
        //     case '2':
        //         $sort_column = 'TM_商品.商品番号';
        //         $sort_direction = 'asc';
        //         break;
        //     case '3':
        //         $sort_column = 'TM_商品.商品番号';
        //         $sort_direction = 'desc';
        //         break;
        // }
 

        //$sort_column = $request->get('sort', 'TM_商品.商品番号');
        //$sort_direction = $request->get('sort_direction', 'asc');
        //$direction = $request->get('direction', 'asc');

        $params = [];
        if ($request->has('prefectures') && $request->input('prefectures') != '') {
            $params['prefectures'] = $request->input('prefectures');
            $params['prefectures'] = array_map('intval', explode(',', $request->input('prefectures')));
        }

        // $params = [];
        // if ($request->has('makers') && $request->input('makers') != '') {
        //     $params['makers'] = $request->input('makers');
        //     $params['makers'] = array_map('intval', explode(',', $request->input('makers')));
        // }
        // if ($request->has('genres') && $request->input('genres') != '') {
        //     $params['genres'] = $request->input('genres');
        //     $params['genres'] = array_map('strval', explode(',', $request->input('genres')));
        // }

        // if ($request->has('no') && $request->input('no') != '') {
        //     $params['no'] = $request->input('no');
        // }

        // if ($request->has('sku') && $request->input('sku') != '') {
        //     $params['sku'] = $request->input('sku');
        // }

        // if ($request->has('name') && $request->input('name') != '') {
        //     $params['name'] = $request->input('name');
        // }

        // if ($request->has('size') && $request->input('size') != '') {
        //     $params['size'] = $request->input('size');
        // }

        // if ($request->has('color') && $request->input('color') != '') {
        //     $params['color'] = $request->input('color');
        // }

        // if ($request->has('brand_name') && $request->input('brand_name') != '') {
        //     $params['brand_name'] = $request->input('brand_name');
        // }

        // return new JsonResponse([
        //     'params' => $params,
        // ]);

        // データ取得
        // $makers = DB::table('TM_仕入先')->whereIn('仕入先', $params['makers'])->get();
        // $makers = DB::table('TM_仕入先')->whereIn('仕入先', $params['makers'])->get();
        
        //$params = [];
        $sort_column = 'photos.id';
        $sort_direction = 'desc';
        $products = $this->searchPhotos(
            $params, 
            $sort_column, $sort_direction, 
            (($page-1)*$per_page), $limit
        );
        
        $total_row_count = $this->searchPhotosCount($params);
        
        // $products_count = count($products);
        // current_page
        $page = $request->get('page', 1);
        $next_page = $page;
        // next, prev 
        $direction = $request->get('direction');
        if (!empty($direction)) {

            if ($direction == 'next') {
                $next_page = $next_page + 1;

            } else if ($direction == 'prev') {
                $next_page = $next_page - 1;
            }

        }

        //$boundary_page = -1;
        $boundary_page = $page-1;
        
        // total_count
        $total_count = $total_row_count;
        // per_page
        $per_page = 30;
        // last_page
        $last_page = ceil($total_count / $per_page);


        $pagination_type = null;
        if ($total_count <= $per_page * 7) {
            $pagination_type = 'type1';

        } else {
            // 両端のページの場合
            if ($next_page <= 3 || $next_page >= ($last_page-2)) {
                $pagination_type = 'type2_edge';
                
                // 両端ページの境界ページからPrev, Nextの場合
                if ($page == 3 && ($direction == 'next')) {
                    $pagination_type = 'type2_middle';
                    // $boundary_page = 4;
                }
                if ($page == ($last_page-2) && ($direction == 'prev')){
                    $pagination_type = 'type2_middle';
                }

            } else {
                $pagination_type = 'type2_middle';
            }

        }

        //dd($products);

        return new JsonResponse([
            //'maker_name' => $makers->メーカー名,
            //'makers' => $params['makers'],
            'data' => $products,
            'current_page' => $next_page,
            'total_row_count' => $total_row_count,
            // 'per_page' => $per_page,
            // 'last_page' => $last_page,
            // 'pagination_type' => $pagination_type,
            // 'boundary_page' => $boundary_page,
            
            // for debug  
            //'page' => (($page-1)*$per_page),       
        ]);        
    }

    /**
     * searchProducts
     */
    private function searchPhotos($params, $sort_column, $sort_direction, $offset, $limit) {

 
        // Using FOR XML PATH (SQL Server 2016 and earlier)
        // $query = DB::table('TM_商品')
        //     ->select(
        //         'TM_商品.商品番号', 'TM_商品.商品名', 'TM_商品.BTOC販売価格', 'TM_商品.バイヤー担当','TM_商品.登録日時', 
        //         'TM_商品ネット.サイズバリエーション', 'TM_商品ネット.カラーバリエーション',
        //         'TM_商品バリ.SKU発注番号', 'TM_商品バリ.SKU原価',
        //         'TM_仕入先.メーカー名',
        //         DB::raw("(SELECT STUFF((SELECT ',' + CONVERT(NVARCHAR(max), ISNULL(TM_商品バリ.サイズ, 'N/A'))
        //                 FROM TM_商品バリ
        //                 WHERE TM_商品バリ.商品番号 = TM_商品.商品番号
        //                 FOR XML PATH('')), 1, 1, '')) AS サイズ"),
        //         DB::raw("(SELECT STUFF((SELECT ',' + CONVERT(NVARCHAR(max), ISNULL(TM_商品バリ.カラー, 'N/A'))
        //                 FROM TM_商品バリ
        //                 WHERE TM_商品バリ.商品番号 = TM_商品.商品番号
        //                 FOR XML PATH('')), 1, 1, '')) AS カラー"),
        //         'TM_コード.コード名称',
        //         'TM_ブランド.ブランド名'
        //     )
        //     // TM_商品ネット
        //     ->leftJoin('TM_商品ネット', function ($join) {
        //         $join->on('TM_商品ネット.商品番号', '=', 'TM_商品.商品番号');
        //     })
        //     // TM_商品バリ
        //     ->leftJoin('TM_商品バリ', function ($join) {
        //         $join->on('TM_商品バリ.商品番号', '=', 'TM_商品.商品番号');
        //     })
        //     // TM_仕入先
        //     ->leftJoin('TM_仕入先', function ($join) {
        //         $join->on('TM_仕入先.仕入先', '=', 'TM_商品.仕入先');
        //     })
        //     // TM_コード
        //     ->leftJoin('TM_コード', function ($join) {
        //         $join->where('TM_コード.コード区分', '=', 60)
        //              ->on('TM_コード.コード', '=', 'TM_商品.制作担当');
        //     })
        //     // TM_ブランド
        //     ->leftJoin('TM_ブランド', function ($join) {
        //         $join->on('TM_ブランド.ブランド', '=', 'TM_商品.ブランドモール表示順');
        //     })
        //     ->groupBy(
        //         'TM_商品.商品番号', 'TM_商品.商品名', 'TM_商品.BTOC販売価格', 'TM_商品.バイヤー担当', 'TM_商品.登録日時',
        //         'TM_商品ネット.サイズバリエーション', 'TM_商品ネット.カラーバリエーション',
        //         'TM_商品バリ.SKU発注番号', 'TM_商品バリ.SKU原価', 
        //         'TM_仕入先.メーカー名',
        //         'TM_コード.コード名称',
        //         'TM_ブランド.ブランド名'
        //     );

        // $filtered = false;
        // if (isset($params['makers']) && $params['makers'] != '') {
        //     // if (isset($params['maker']) && $params['maker'] != '') {
        //     //     $query->where('TM_商品.仕入先', '=', $params['maker']);
        //     // }
        //     $query->whereIn('TM_商品.仕入先', $params['makers']);
        //     $filtered = true;
        // }

        // if (isset($params['genres']) && $params['genres'] != '') {
        //     $query->whereIn('TM_商品.Amazon登録', $params['genres']);
        //     $filtered = true;
        // }

        // if (isset($params['no']) && $params['no'] != '') {
        //     // $query->whereLike('TM_商品.商品番号', $params['no'] );
        //     $query->where('TM_商品.商品番号', 'LIKE', '%'. $params['no'] . '%');
        //     $filtered = true;
        // }

        // if (isset($params['sku']) && $params['sku'] != '') {
        //     $query->where('TM_商品バリ.SKU発注番号', 'LIKE', '%'. $params['sku'] . '%');
        //     $filtered = true;
        // }

        // if (isset($params['name']) && $params['name'] != '') {
        //     $query->where('TM_商品.商品名', 'LIKE', '%'. $params['name'] . '%');
        //     $filtered = true;
        // }

        // if (isset($params['size']) && $params['size'] != '') {
        //     $query->where('TM_商品ネット.サイズバリエーション', 'LIKE', '%'. $params['size'] . '%');
        //     $filtered = true;
        // }

        // if (isset($params['color']) && $params['color'] != '') {
        //     $query->where('TM_商品ネット.カラーバリエーション', 'LIKE', '%'. $params['color'] . '%');
        //     $filtered = true;
        // }

        // if (isset($params['brand_name']) && $params['brand_name'] != '') {
        //     $query->where('TM_ブランド.ブランド名', 'LIKE', '%'. $params['brand_name'] . '%');
        //     $filtered = true;
        // }
        
        // if (!$filtered) {
        //     // 検索条件が無い場合は、取得しない
        //     $query->where('TM_商品.商品番号', '0');
        // } 

        $query = DB::table('photos');
        if (isset($params['prefectures']) && $params['prefectures'] != '') {
            $query->whereIn('pref_code', $params['prefectures']);
            $filtered = true;
        }

        $photos = $query->offset($offset)
                ->limit($limit)
                ->orderBy($sort_column, $sort_direction)
                ->get();

        // dd($products);

        return $photos;
        
    }    

    /**
     * searchPhotosCount
     */
    private function searchPhotosCount($params) {

        // データ取得
        // Using STRING_AGG (SQL Server 2017 and later)

        // Using FOR XML PATH (SQL Server 2016 and earlier)
        // $query = DB::table('TM_商品')
        //     ->select(
        //         'TM_商品.商品番号', 'TM_商品.商品名', 'TM_商品.BTOC販売価格', 'TM_商品.バイヤー担当',
        //         'TM_商品ネット.サイズバリエーション', 'TM_商品ネット.カラーバリエーション',
        //         'TM_商品バリ.SKU発注番号', 'TM_商品バリ.SKU原価',
        //         'TM_仕入先.メーカー名',
        //         DB::raw("(SELECT STUFF((SELECT ',' + CONVERT(NVARCHAR(max), ISNULL(TM_商品バリ.サイズ, 'N/A'))
        //                 FROM TM_商品バリ
        //                 WHERE TM_商品バリ.商品番号 = TM_商品.商品番号
        //                 FOR XML PATH('')), 1, 1, '')) AS サイズ"),
        //         DB::raw("(SELECT STUFF((SELECT ',' + CONVERT(NVARCHAR(max), ISNULL(TM_商品バリ.カラー, 'N/A'))
        //                 FROM TM_商品バリ
        //                 WHERE TM_商品バリ.商品番号 = TM_商品.商品番号
        //                 FOR XML PATH('')), 1, 1, '')) AS カラー"),
        //         'TM_コード.コード名称',
        //         'TM_ブランド.ブランド名'
        //     )
        //     // TM_商品ネット
        //     ->leftJoin('TM_商品ネット', function ($join) {
        //         $join->on('TM_商品ネット.商品番号', '=', 'TM_商品.商品番号');
        //     })
        //     // TM_商品バリ
        //     ->leftJoin('TM_商品バリ', function ($join) {
        //         $join->on('TM_商品バリ.商品番号', '=', 'TM_商品.商品番号');
        //     })
        //     // TM_仕入先
        //     ->leftJoin('TM_仕入先', function ($join) {
        //         $join->on('TM_仕入先.仕入先', '=', 'TM_商品.仕入先');
        //     })
        //     // TM_コード
        //     ->leftJoin('TM_コード', function ($join) {
        //         $join->where('TM_コード.コード区分', '=', 60)
        //              ->on('TM_コード.コード', '=', 'TM_商品.制作担当');
        //     })
        //     // TM_ブランド
        //     ->leftJoin('TM_ブランド', function ($join) {
        //         $join->on('TM_ブランド.ブランド', '=', 'TM_商品.ブランドモール表示順');
        //     })
        //     ->groupBy(
        //         'TM_商品.商品番号', 'TM_商品.商品名', 'TM_商品.BTOC販売価格', 'TM_商品.バイヤー担当',
        //         'TM_商品ネット.サイズバリエーション', 'TM_商品ネット.カラーバリエーション',
        //         'TM_商品バリ.SKU発注番号', 'TM_商品バリ.SKU原価', 
        //         'TM_仕入先.メーカー名',
        //         'TM_コード.コード名称',
        //         'TM_ブランド.ブランド名'
        //     );

        // $filtered = false;
        // if (isset($params['makers']) && $params['makers'] != '') {
        //     $query->whereIn('TM_商品.仕入先', $params['makers']);
        //     $filtered = true;
        // }

        // if (isset($params['genres']) && $params['genres'] != '') {
        //     $query->whereIn('TM_商品.Amazon登録', $params['genres']);
        //     $filtered = true;
        // }

        // if (isset($params['no']) && $params['no'] != '') {
        //     $query->where('TM_商品.商品番号', 'LIKE', '%'. $params['no'] . '%');
        //     $filtered = true;
        // }

        // if (isset($params['sku']) && $params['sku'] != '') {
        //     $query->where('TM_商品バリ.SKU発注番号', 'LIKE', '%'. $params['sku'] . '%');
        //     $filtered = true;
        // }

        // if (isset($params['name']) && $params['name'] != '') {
        //     $query->where('TM_商品.商品名', 'LIKE', '%'. $params['name'] . '%');
        //     $filtered = true;
        // }

        // if (isset($params['size']) && $params['size'] != '') {
        //     $query->where('TM_商品ネット.サイズバリエーション', 'LIKE', '%'. $params['size'] . '%');
        //     $filtered = true;
        // }

        // if (isset($params['color']) && $params['color'] != '') {
        //     $query->where('TM_商品ネット.カラーバリエーション', 'LIKE', '%'. $params['color'] . '%');
        //     $filtered = true;
        // }

        // if (isset($params['brand_name']) && $params['brand_name'] != '') {
        //     $query->where('TM_ブランド.ブランド名', 'LIKE', '%'. $params['brand_name'] . '%');
        //     $filtered = true;
        // }
        
        // if (!$filtered) {
        //     // 検索条件が無い場合は、取得しない
        //     $query->where('TM_商品.商品番号', '0');
        // } 
    
        // $query->where('TM_商品.仕入先', '=', '203');
        //$query->where('TM_商品ネット.カラーバリエーション', 'like', '%働くくるま%');

        $query = DB::table('photos');
        if (isset($params['prefectures']) && $params['prefectures'] != '') {
            $query->whereIn('pref_code', $params['prefectures']);
            $filtered = true;
        }

        $total_row_count = count($query->get());

        // dd($products);

        return $total_row_count;
        
    }
    
}
