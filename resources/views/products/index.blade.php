@extends('layouts.app')

@section('content')

<h1>商品情報一覧画面</h1>

<a href="create" class="btn-outline-primary">新規登録</a>

<div class="search">
    <div>
        <div class="form-group">
            <label for="keyword">商品名</label>
            <div>
                <input type="text" name="keyword" id="keyword">
            </div>
        </div>

        <div>
            <div class="form-group">
                <label for="company">企業名</label>
                <div>
                    <select name="company" id="company" data-toggle="select">
                        <option value="">選択してください</option>
                        @foreach ($companies as $company)
                        <option value="{{ $company->id }}" @if($company=='{{ $company->company_name }}') selected @endif>{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>    
            </div>
        </div>

        <div>
            <div class="form-group">
                <label for="price">価格</label>
                <div>
                    <input type="text" name="min_price" id="min_price">
                    <span class="price-unit">〜</span>
                    <input type="text" name="max_price" id="max_price">
                </div>
            </div>
        </div>

        <div>
            <div class="form-group">
                <label for="stock">在庫数</label>
                <div>
                    <input type="text" name="min_stock" id="min_stock">
                    <span class="stock-unit">〜</span>
                    <input type="text" name="max_stock" id="max_stock">
                </div>
            </div>
        </div>

        <div>
            <input id="search_button" class="submit-btn" type="submit" value="検索" />
        </div>
    </div>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>@sortablelink('id')</th>
            <th>@sortablelink('img_path','商品画像')</th>
            <th>@sortablelink('product_name','商品名')</th>
            <th>@sortablelink('price','価格')</th>
            <th>@sortablelink('stock','在庫数')</th>
            <th>@sortablelink('company_name','メーカー')</th>
        </tr>
    </thead>
    <tbody id="tbody">
    @foreach ($products as $product)
        <tr>
            <td class="product_id">{{ $product->id }}</td>
            <td><img src="{{ asset('storage/' . $product->img_path) }}" width="15%"></td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->company_name }}</td>
            <td><a href="{{ route('products.show', ['id'=>$product->id]) }}" class="btn btn-primary">詳細表示</a></td>
            <td> 
                <button type='button' class='btn btn-danger destory-button'>削除</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection
