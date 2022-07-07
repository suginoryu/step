@extends('layouts.app')

@section('content')

<h1>商品情報詳細画面</h1>

<table class="table table-striped">
 <thead>
  <tr>
   <th>商品情報ID</th>
   <th>商品画像</th>
   <th>商品名</th>
   <th>メーカー</th>
   <th>価格</th>
   <th>在庫数</th>
   <th>コメント</th>
  </tr>
 </thead>
 <tbody>
  <tr>
   <td>{{ $product->id }}</td>
   <td><img src="{{ asset('storage/' . $product->img_path) }}" width="15%"></td>
   <td>{{ $product->product_name }}</td>
   <td>{{ $product->company_name }}</td>
   <td>{{ $product->price }}</td>
   <td>{{ $product->stock }}</td>
   <td>{{ $product->comment }}</td>
  </tr>
 </tbody>
</table>
<form method="GET" action="{{ route('products.edit', ['id' => $product->id] ) }}">
@csrf
<input type="submit" value="編集" class="btn btn-info">

<button type="button" class="btn btn-primary" onClick="history.back()">戻る</button>

@endsection