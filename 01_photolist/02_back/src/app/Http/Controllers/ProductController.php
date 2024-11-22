<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Supplier;

use App\Models\Product;


class ProductController extends Controller
{

    public function search(Request $request)
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

Collation：
Japanese_CI_AS




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


    public function searchTest(Request $request)
    {

        $limit = 20;
        $per_page = 20;
        $page = $request->get('page', 1);

        $sort_column = "";
        $sort_direction = "";
        
        // 画面から指定されたソートキーを取得
        $sort_key =  $request->get('sort', '1');
        switch ($sort_key) {
            case '0':
                $sort_column = 'TM_商品.登録日時';
                $sort_direction = 'asc';
                break;
            case '1':
                $sort_column = 'TM_商品.登録日時';
                $sort_direction = 'desc';
                break;
            case '2':
                $sort_column = 'TM_商品.商品番号';
                $sort_direction = 'asc';
                break;
            case '3':
                $sort_column = 'TM_商品.商品番号';
                $sort_direction = 'desc';
                break;
        }
 

        //$sort_column = $request->get('sort', 'TM_商品.商品番号');
        //$sort_direction = $request->get('sort_direction', 'asc');
        //$direction = $request->get('direction', 'asc');

        $params = [];
        if ($request->has('makers') && $request->input('makers') != '') {
            $params['makers'] = $request->input('makers');
            $params['makers'] = array_map('intval', explode(',', $request->input('makers')));
        }
        if ($request->has('genres') && $request->input('genres') != '') {
            $params['genres'] = $request->input('genres');
            $params['genres'] = array_map('strval', explode(',', $request->input('genres')));
        }

        if ($request->has('no') && $request->input('no') != '') {
            $params['no'] = $request->input('no');
        }

        if ($request->has('sku') && $request->input('sku') != '') {
            $params['sku'] = $request->input('sku');
        }

        if ($request->has('name') && $request->input('name') != '') {
            $params['name'] = $request->input('name');
        }

        if ($request->has('size') && $request->input('size') != '') {
            $params['size'] = $request->input('size');
        }

        if ($request->has('color') && $request->input('color') != '') {
            $params['color'] = $request->input('color');
        }

        if ($request->has('brand_name') && $request->input('brand_name') != '') {
            $params['brand_name'] = $request->input('brand_name');
        }

        // return new JsonResponse([
        //     'params' => $params,
        // ]);

        // データ取得
        // $makers = DB::table('TM_仕入先')->whereIn('仕入先', $params['makers'])->get();
        // $makers = DB::table('TM_仕入先')->whereIn('仕入先', $params['makers'])->get();
        
        $products = $this->searchProducts(
            $params, 
            $sort_column, $sort_direction, 
            (($page-1)*$per_page), $limit
        );
        
        $total_row_count = $this->searchProductsCount($params);
        
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
        $per_page = 20;
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
    private function searchProducts($params, $sort_column, $sort_direction, $offset, $limit) {

        //dd($params);
        // データ取得
        // Using STRING_AGG (SQL Server 2017 and later)
        /*
        $query = DB::table('TM_商品')
            ->select(
                'TM_商品.商品番号', 'TM_商品.商品名', 'TM_商品.BTOC販売価格', 'TM_商品.バイヤー担当',
                'TM_商品バリ.SKU発注番号', 'TM_商品バリ.SKU原価',
                'TM_仕入先.メーカー名',
                DB::raw('STRING_AGG(CONVERT(NVARCHAR(max), ISNULL(TM_商品ネット.サイズバリエーション,\'N/A\')), \',\') AS サイズバリエーション'),
                DB::raw('STRING_AGG(CONVERT(NVARCHAR(max), ISNULL(TM_商品ネット.カラーバリエーション,\'N/A\')), \',\') AS カラーバリエーション'),
                'TM_コード.コード名称' 
            )
            // TM_商品ネット
            ->leftJoin('TM_商品ネット', function ($join) {
                $join->on('TM_商品ネット.商品番号', '=', 'TM_商品.商品番号');
            })
            // TM_商品バリ
            ->leftJoin('TM_商品バリ', function ($join) {
                $join->on('TM_商品バリ.商品番号', '=', 'TM_商品.商品番号');
            })
            // TM_仕入先
            ->leftJoin('TM_仕入先', function ($join) {
                $join->on('TM_仕入先.仕入先', '=', 'TM_商品.仕入先');
            })
            // TM_コード
            ->leftJoin('TM_コード', function ($join) {
                $join->where('TM_コード.コード区分', '=', 60)
                     ->on('TM_コード.コード', '=', 'TM_商品.制作担当');
            })
            ->groupBy(
                'TM_商品.商品番号', 'TM_商品.商品名', 'TM_商品.BTOC販売価格', 'TM_商品.バイヤー担当',
                'TM_商品バリ.SKU発注番号', 'TM_商品バリ.SKU原価', 
                'TM_仕入先.メーカー名',
                'TM_コード.コード名称'
            );


        $query->where('TM_商品.仕入先', '=', '203');
        $query->where('TM_商品ネット.カラーバリエーション', 'like', '%働くくるま%');
        */


        // Using FOR XML PATH (SQL Server 2016 and earlier)
        $query = DB::table('TM_商品')
            ->select(
                'TM_商品.商品番号', 'TM_商品.商品名', 'TM_商品.BTOC販売価格', 'TM_商品.バイヤー担当','TM_商品.登録日時', 
                'TM_商品ネット.サイズバリエーション', 'TM_商品ネット.カラーバリエーション',
                'TM_商品バリ.SKU発注番号', 'TM_商品バリ.SKU原価',
                'TM_仕入先.メーカー名',
                DB::raw("(SELECT STUFF((SELECT ',' + CONVERT(NVARCHAR(max), ISNULL(TM_商品バリ.サイズ, 'N/A'))
                        FROM TM_商品バリ
                        WHERE TM_商品バリ.商品番号 = TM_商品.商品番号
                        FOR XML PATH('')), 1, 1, '')) AS サイズ"),
                DB::raw("(SELECT STUFF((SELECT ',' + CONVERT(NVARCHAR(max), ISNULL(TM_商品バリ.カラー, 'N/A'))
                        FROM TM_商品バリ
                        WHERE TM_商品バリ.商品番号 = TM_商品.商品番号
                        FOR XML PATH('')), 1, 1, '')) AS カラー"),
                'TM_コード.コード名称',
                'TM_ブランド.ブランド名'
            )
            // TM_商品ネット
            ->leftJoin('TM_商品ネット', function ($join) {
                $join->on('TM_商品ネット.商品番号', '=', 'TM_商品.商品番号');
            })
            // TM_商品バリ
            ->leftJoin('TM_商品バリ', function ($join) {
                $join->on('TM_商品バリ.商品番号', '=', 'TM_商品.商品番号');
            })
            // TM_仕入先
            ->leftJoin('TM_仕入先', function ($join) {
                $join->on('TM_仕入先.仕入先', '=', 'TM_商品.仕入先');
            })
            // TM_コード
            ->leftJoin('TM_コード', function ($join) {
                $join->where('TM_コード.コード区分', '=', 60)
                     ->on('TM_コード.コード', '=', 'TM_商品.制作担当');
            })
            // TM_ブランド
            ->leftJoin('TM_ブランド', function ($join) {
                $join->on('TM_ブランド.ブランド', '=', 'TM_商品.ブランドモール表示順');
            })
            ->groupBy(
                'TM_商品.商品番号', 'TM_商品.商品名', 'TM_商品.BTOC販売価格', 'TM_商品.バイヤー担当', 'TM_商品.登録日時',
                'TM_商品ネット.サイズバリエーション', 'TM_商品ネット.カラーバリエーション',
                'TM_商品バリ.SKU発注番号', 'TM_商品バリ.SKU原価', 
                'TM_仕入先.メーカー名',
                'TM_コード.コード名称',
                'TM_ブランド.ブランド名'
            );

        $filtered = false;
        if (isset($params['makers']) && $params['makers'] != '') {
            // if (isset($params['maker']) && $params['maker'] != '') {
            //     $query->where('TM_商品.仕入先', '=', $params['maker']);
            // }
            $query->whereIn('TM_商品.仕入先', $params['makers']);
            $filtered = true;
        }

        if (isset($params['genres']) && $params['genres'] != '') {
            $query->whereIn('TM_商品.Amazon登録', $params['genres']);
            $filtered = true;
        }

        if (isset($params['no']) && $params['no'] != '') {
            // $query->whereLike('TM_商品.商品番号', $params['no'] );
            $query->where('TM_商品.商品番号', 'LIKE', '%'. $params['no'] . '%');
            $filtered = true;
        }

        if (isset($params['sku']) && $params['sku'] != '') {
            $query->where('TM_商品バリ.SKU発注番号', 'LIKE', '%'. $params['sku'] . '%');
            $filtered = true;
        }

        if (isset($params['name']) && $params['name'] != '') {
            $query->where('TM_商品.商品名', 'LIKE', '%'. $params['name'] . '%');
            $filtered = true;
        }

        if (isset($params['size']) && $params['size'] != '') {
            $query->where('TM_商品ネット.サイズバリエーション', 'LIKE', '%'. $params['size'] . '%');
            $filtered = true;
        }

        if (isset($params['color']) && $params['color'] != '') {
            $query->where('TM_商品ネット.カラーバリエーション', 'LIKE', '%'. $params['color'] . '%');
            $filtered = true;
        }

        if (isset($params['brand_name']) && $params['brand_name'] != '') {
            $query->where('TM_ブランド.ブランド名', 'LIKE', '%'. $params['brand_name'] . '%');
            $filtered = true;
        }
        
        if (!$filtered) {
            // 検索条件が無い場合は、取得しない
            $query->where('TM_商品.商品番号', '0');
        } 

        // $query->where('TM_商品.仕入先', '=', '203');
        //$query->where('TM_商品ネット.カラーバリエーション', 'like', '%働くくるま%');

        

        $products = $query->offset($offset)
                ->limit($limit)
                ->orderBy($sort_column, $sort_direction)
                ->get();

        // dd($products);

        return $products;
        
    }    

    /**
     * searchProductsCount
     */
    private function searchProductsCount($params) {

        // データ取得
        // Using STRING_AGG (SQL Server 2017 and later)

        // Using FOR XML PATH (SQL Server 2016 and earlier)
        $query = DB::table('TM_商品')
            ->select(
                'TM_商品.商品番号', 'TM_商品.商品名', 'TM_商品.BTOC販売価格', 'TM_商品.バイヤー担当',
                'TM_商品ネット.サイズバリエーション', 'TM_商品ネット.カラーバリエーション',
                'TM_商品バリ.SKU発注番号', 'TM_商品バリ.SKU原価',
                'TM_仕入先.メーカー名',
                DB::raw("(SELECT STUFF((SELECT ',' + CONVERT(NVARCHAR(max), ISNULL(TM_商品バリ.サイズ, 'N/A'))
                        FROM TM_商品バリ
                        WHERE TM_商品バリ.商品番号 = TM_商品.商品番号
                        FOR XML PATH('')), 1, 1, '')) AS サイズ"),
                DB::raw("(SELECT STUFF((SELECT ',' + CONVERT(NVARCHAR(max), ISNULL(TM_商品バリ.カラー, 'N/A'))
                        FROM TM_商品バリ
                        WHERE TM_商品バリ.商品番号 = TM_商品.商品番号
                        FOR XML PATH('')), 1, 1, '')) AS カラー"),
                'TM_コード.コード名称',
                'TM_ブランド.ブランド名'
            )
            // TM_商品ネット
            ->leftJoin('TM_商品ネット', function ($join) {
                $join->on('TM_商品ネット.商品番号', '=', 'TM_商品.商品番号');
            })
            // TM_商品バリ
            ->leftJoin('TM_商品バリ', function ($join) {
                $join->on('TM_商品バリ.商品番号', '=', 'TM_商品.商品番号');
            })
            // TM_仕入先
            ->leftJoin('TM_仕入先', function ($join) {
                $join->on('TM_仕入先.仕入先', '=', 'TM_商品.仕入先');
            })
            // TM_コード
            ->leftJoin('TM_コード', function ($join) {
                $join->where('TM_コード.コード区分', '=', 60)
                     ->on('TM_コード.コード', '=', 'TM_商品.制作担当');
            })
            // TM_ブランド
            ->leftJoin('TM_ブランド', function ($join) {
                $join->on('TM_ブランド.ブランド', '=', 'TM_商品.ブランドモール表示順');
            })
            ->groupBy(
                'TM_商品.商品番号', 'TM_商品.商品名', 'TM_商品.BTOC販売価格', 'TM_商品.バイヤー担当',
                'TM_商品ネット.サイズバリエーション', 'TM_商品ネット.カラーバリエーション',
                'TM_商品バリ.SKU発注番号', 'TM_商品バリ.SKU原価', 
                'TM_仕入先.メーカー名',
                'TM_コード.コード名称',
                'TM_ブランド.ブランド名'
            );

        $filtered = false;
        if (isset($params['makers']) && $params['makers'] != '') {
            $query->whereIn('TM_商品.仕入先', $params['makers']);
            $filtered = true;
        }

        if (isset($params['genres']) && $params['genres'] != '') {
            $query->whereIn('TM_商品.Amazon登録', $params['genres']);
            $filtered = true;
        }

        if (isset($params['no']) && $params['no'] != '') {
            $query->where('TM_商品.商品番号', 'LIKE', '%'. $params['no'] . '%');
            $filtered = true;
        }

        if (isset($params['sku']) && $params['sku'] != '') {
            $query->where('TM_商品バリ.SKU発注番号', 'LIKE', '%'. $params['sku'] . '%');
            $filtered = true;
        }

        if (isset($params['name']) && $params['name'] != '') {
            $query->where('TM_商品.商品名', 'LIKE', '%'. $params['name'] . '%');
            $filtered = true;
        }

        if (isset($params['size']) && $params['size'] != '') {
            $query->where('TM_商品ネット.サイズバリエーション', 'LIKE', '%'. $params['size'] . '%');
            $filtered = true;
        }

        if (isset($params['color']) && $params['color'] != '') {
            $query->where('TM_商品ネット.カラーバリエーション', 'LIKE', '%'. $params['color'] . '%');
            $filtered = true;
        }

        if (isset($params['brand_name']) && $params['brand_name'] != '') {
            $query->where('TM_ブランド.ブランド名', 'LIKE', '%'. $params['brand_name'] . '%');
            $filtered = true;
        }
        
        if (!$filtered) {
            // 検索条件が無い場合は、取得しない
            $query->where('TM_商品.商品番号', '0');
        } 
    
        // $query->where('TM_商品.仕入先', '=', '203');
        //$query->where('TM_商品ネット.カラーバリエーション', 'like', '%働くくるま%');

        

        $total_row_count = count($query->get());

        // dd($products);

        return $total_row_count;
        
    }
    
}
