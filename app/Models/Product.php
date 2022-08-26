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
        'comment',
        'company_name'
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
}
