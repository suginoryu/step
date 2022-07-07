@extends('layouts.app')

@section('content')

<h1>商品情報編集画面</h1>

    <!-- form -->
    <form method="post" action="{{ route('products.update', ['id' => $product->id] ) }}" enctype="multipart/form-data">
    @csrf

        <div class="form-group">
            <label>商品情報ID</label>
            <input type="text" name="id" value="{{ $product->id }}" >
        </div>
        
        <div class="form-group">
            <label>商品名</label>
            <input type="text" name="product_name" value="{{ $product->product_name }}" class="form-control">
        </div>
        @if ($errors->first('product_name'))   <!-- ここ追加 -->
        <p class="validation">※{{$errors->first('product_name')}}</p>
        @endif

        <div class="form-group">
        <label>メーカー</label>
        <select class="form-control" name="company_id">
            <option style="display: none;">選択してください</option>
            @foreach ($companies as $company)
            @if($company->id === $product->company_id)
            <option value="{{ $company->id }}" selected>{{ $company->company_name }}</option>
            @else
            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
            @endif
            @endforeach
        </select>
        </div>
        @if ($errors->first('company_id'))   <!-- ここ追加 -->
        <p class="validation">※{{$errors->first('company_id')}}</p>
        @endif


        <div class="form-group">
            <label>価格</label>
            <input type="text" name="price" value="{{ $product->price }}" class="form-control">
        </div>
        @if ($errors->first('price'))   <!-- ここ追加 -->
        <p class="validation">※{{$errors->first('price')}}</p>
        @endif


        <div class="form-group">
            <label>在庫数</label>
            <input type="text" name="stock" value="{{ $product->stock }}" class="form-control">
        </div>
        @if ($errors->first('stock'))   <!-- ここ追加 -->
        <p class="validation">※{{$errors->first('stock')}}</p>
        @endif

        <div class="form-group">
            <label>コメント</label>
            <textarea name="comment" class="form-control">{{ $product->comment }}</textarea>
        </div>

        <div class="form-group">
            <label>商品画像</label>
            <img src="{{ asset('storage/' . $product->img_path) }}" width="25%">
            <input type="file" name="img_path">
        </div>


        <input type="submit" value="更新" class="btn btn-info">

        <button type="button" class="btn btn-primary" onClick="history.back()">戻る</button>
    </form>
@stop