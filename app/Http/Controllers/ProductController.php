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
        $companies = Company::all();

        return view('products.index',compact('products','companies'));
    }

    public function create(Request $request)
    {
        $companies = Company::all();
        return view('products.create',compact('companies'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([      
            'product_name' => ['required'],
            'price' => ['required','integer'],
            'stock' => ['required','integer'],
            'company_id' => ['required','exists:companies,id'],
        ],
        [
            'product_name.required' => '商品名を入力してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '数字で入力してください',
            'stock.required' => '在庫数を入力してください',
            'stock.integer' => '数字で入力してください',
            'company_id.required' => '企業名を選択してください',
            'company_id.exists' => '企業一覧から選択してください',
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
        ],
        [
            'product_name.required' => '商品名を入力してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '数字で入力してください',
            'stock.required' => '在庫数を入力してください',
            'stock.integer' => '数字で入力してください',
            'company_id.required' => '企業名を選択してください',
            'company_id.exists' => '企業一覧から選択してください',
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
        $products = Product::getSearchProducts($keyword, $company, $min_price, $max_price, $min_stock, $max_stock);
        $companies = Company::all();
        return response()->json(['products' => $products, 'companies' => $companies]);
    }
}