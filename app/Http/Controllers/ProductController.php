<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->direction == null) {
            $direction = 'desc';
        } else {
            $direction = $request->direction;
        }
        if ($request->sort == null) {
            $sort = 'id';
        } else {
            $sort = $request->sort;
        }

        $products = Product::getProducts($direction, $sort);
        $companies = $this->getCompanies();

        return view('products.index',compact('products','companies'));
    }

    public function create(Request $request)
    {
        $companies = Company::get();
        return view('products.create',compact('companies'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([      
            'product_name' => ['required'],
            'price' => ['required','integer'],
            'stock' => ['required','integer'],
            'company_id' => ['required','exists:companies,id'],
        ]);

        $img = $request->file('img_path');
        if (isset($img)) {
            $path = $img->store('img','public');
        } else {
            $path = "NULL";
        }
        try {
            DB::beginTransaction();
            Product::create([
                'company_id' => $request->company_id,
                'img_path' => $path,
                'product_name' => $request->product_name,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
        }
        return redirect('products');
    }

    public function show ($id) 
    {
        $product = Product::getProduct($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::getProduct($id);
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies')); 
    }

    public function update(Request $request, $id)
    {
        $validator = $request->validate([       
            'product_name' => ['required'],
            'price' => ['required','integer'],
            'stock' => ['required','integer'],
            'company_id' => ['required','exists:companies,id'],
        ]);

        $img = $request->file('img_path');
        $products = Product::find($id);
        $path = $products->img;
        if (isset($img)) {
            \Storage::disk('public')->delete($path);
            $path = $img->store('img','public');
        } else {
            $path = "NULL";
        }
        try {
            DB::beginTransaction();

            $products->update([
                'id' => $request->id,
                'product_name' => $request->product_name,
                'company_id' => $request->company_id,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
                'img_path' => $path,
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
        }    
        return redirect('products'); 
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $products = Product::find($id);
            $products->delete();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
        }
        return redirect('products');
    }

    public function search(Request $request) {
        $keyword = $request->input('keyword');
        $company = $request->input('company');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $min_stock = $request->input('min_stock');
        $max_stock = $request->input('max_stock');
        $products = $this->getSearchProducts($keyword, $company, $min_price, $max_price, $min_stock, $max_stock);
        $companies = $this->getCompanies();
        return response()->json(['products' => $products, 'companies' => $companies]);
    }

    public static function getCompanies() {
        $companies = Company::all();
        return $companies;
    } 

    // 商品検索時
    public static function getSearchProducts($keyword, $company, $min_price, $max_price, $min_stock, $max_stock) {
        $query = DB::table('products');
        $query->select('products.id','products.company_id','products.img_path','products.product_name','products.price','products.stock','products.comment','companies.id as company_id','companies.company_name');
        $query->join('companies', 'products.company_id', '=', 'companies.id');

        if(!empty($company)) {
            $query->where('company_id', '=', $company);
        }

        if (!empty($keyword)) {
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }
        
        //  価格の最高と最低がある時
         if (!empty($min_price)&&!empty($max_price)) {
            $query->whereBetween('price',[$min_price,$max_price]);
        // 最低のみある時
        } elseif(!empty($min_price)) {
            $query->where('price', '>=', $min_price);
        // 最高のみある時
        } elseif(!empty($max_price)) {
            $query->where('price', '<=', $max_price);
        }

         // 在庫数の最高と最低がある時
         if (!empty($min_stock)&&!empty($max_stock)) {
            $query->whereBetween('stock',[$min_stock,$max_stock]);
        // 最低のみある時
        } elseif(!empty($min_stock)) {
            $query->where('stock', '>=', $min_stock);
        // 最高のみある時
        } elseif(!empty($max_stock)) {
            $query->where('stock', '<=', $max_stock);
        }

        $products = $query->get();
        return $products; 
    }

    //価格と在庫数一致
    public static function setFromQuery($query, $min, $max, $column) {
        // 最高と最低がある時
        if (!empty($min)&&!empty($max)) {
            $query->whereBetween($column,[$min,$max]);
        // 最低のみある時
        } elseif(!empty($min)) {
            $query->where($column, '>=', $min);
        // 最高のみある時
        } elseif(!empty($max)) {
            $query->where($column, '<=', $max);
        }
        return $query;
    }
}