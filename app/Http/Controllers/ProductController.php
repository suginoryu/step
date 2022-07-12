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
        $keyword = $request->input('keyword');
        $company = $request->input('company');
        $query = DB::table('products');
        $query->select('products.id','products.company_id','products.img_path','products.product_name','products.price','products.stock','products.comment','companies.id as company_id','companies.company_name');
        $query->join('companies', 'products.company_id', '=', 'companies.id');

        if(!empty($company)) {
            $query->where('company_id', '=', $company);
        }

        if (!empty($keyword)) {
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }

        $companies = Company::all();
        $products = $query->get();
        return view('products.index',compact('products','keyword','company','companies'));
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

    public function show ($id) {
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
}