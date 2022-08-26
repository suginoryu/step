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
        $product = $this->findProduct($product_id);
        if($product->stock <= 0) {
            return '購入NG';
        }
        try {
            DB::beginTransaction();
            $this->addSales($product_id);
            $product->stock = $product->stock - 1;
            $product->save();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
        }
        return '購入OK';
   }

   private function addSales($product_id)
   {
        Sale::create(['product_id' => $product_id]);
   }

   private function findProduct($product_id)
   {
        return Product::find($product_id);
   }

}
