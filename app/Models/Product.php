<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use Sortable;

    protected $dates = [ 'deleted_at' ];
    protected $table = 'products';

    protected $fillable = [
        'id',
        'company_id',
        'img_path',
        'product_name',
        'price',
        'stock',
        'comment'
    ];

    public static function getProduct($product_id) {
        $query = DB::table('products');
        $query->select(
            'products.id',
            'products.company_id',
            'products.img_path',
            'products.product_name',
            'products.price',
            'products.stock',
            'products.comment',
            'companies.id as company_id',
            'companies.company_name'
        );
        $query->join('companies', 'products.company_id', '=', 'companies.id');
        $query->where('products.id', '=', $product_id);

        return $query->first();
    }

    //商品全件取得
    public static function getProducts($direction, $sort) {
        $query = DB::table('products');
        $query->select('products.id','products.company_id','products.img_path','products.product_name','products.price','products.stock','products.comment','companies.id as company_id','companies.company_name');
        $query->join('companies', 'products.company_id', '=', 'companies.id');
        $query->orderBy('products.' . $sort, $direction);
        $products = $query->get();
        return $products;
    }

    // 商品検索時
    public static function getSearchProducts($keyword, $company, $min_price, $max_price, $min_stock, $max_stock) {
        $query = DB::table('products');
        $query->select('products.id','products.company_id','products.img_path','products.product_name','products.price','products.stock','products.comment','companies.id as company_id','companies.company_name');
        $query->join('companies', 'products.company_id', '=', 'companies.id');

        if(isset($company)) {
            $query->where('company_id', '=', $company);
        }

        if (isset($keyword)) {
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }
        
        //  価格の最高と最低がある時
         if (isset($min_price)&&isset($max_price)) {
            $query->whereBetween('price',[$min_price,$max_price]);
        // 最低のみある時
        } elseif (isset($min_price)) {
            $query->where('price', '>=', $min_price);
        // 最高のみある時
        } elseif (isset($max_price)) {
            $query->where('price', '<=', $max_price);
        }

         // 在庫数の最高と最低がある時
         if (isset($min_stock)&&isset($max_stock)) {
            $query->whereBetween('stock',[$min_stock,$max_stock]);
        // 最低のみある時
        } elseif (isset($min_stock)) {
            $query->where('stock', '>=', $min_stock);
        // 最高のみある時
        } elseif (isset($max_stock)) {
            $query->where('stock', '<=', $max_stock);
        }

        $products = $query->get();
        return $products; 
    }
}
