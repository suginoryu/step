<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    // indexアクションを定義（indexメソッドの定義と同義)
   public function purchase(Request $request)
   {
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        if($product->stock <= 0) {
            return '購入NG';
        }
        try {
            DB::beginTransaction();
            Sale::create(['product_id' => $product_id]);
            $product->stock = $product->stock - 1;
            $product->save();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
        }
        return '購入OK';
   }
}
