@extends('layouts.app')

@section('content')

<h1>商品情報一覧画面</h1>

<a href="create" class="btn-outline-primary">新規登録</a>

<div class="search">
        <form action="{{ route('products.index') }}" method="GET">
            @csrf

            <div class="form-group">
                <div>
                    <label for="keyword">商品名</label>
                    <div>
                        <input type="text" name="keyword" id="keyword" value="{{ $keyword }}">
                    </div>
                </div>

                <div>
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

                <div>
                    <input type="submit" class="btn btn-secondary" value="検索">
                </div>
            </div>
        </form>
    </div>

<table class="table table-striped">
 <thead>
  <tr>
   <th>id</th>
   <th>商品画像</th>
   <th>商品名</th>
   <th>価格</th>
   <th>在庫数</th>
   <th>メーカー</th>
  </tr>
 </thead>
 <tbody>
@foreach ($products as $products)
  <tr>
   <td>{{ $products->id }}</td>
   <td><img src="{{ asset('storage/' . $products->img_path) }}" width="15%"></td>
   <td>{{ $products->product_name }}</td>
   <td>{{ $products->price }}</td>
   <td>{{ $products->stock }}</td>
   <td>{{ $products->company_name }}</td>
   <td><a href="{{ route('products.show', ['id'=>$products->id]) }}" class="btn btn-primary">詳細表示</a></td>
   <td><form onsubmit="return confirm('本当に削除しますか？')" action="{{ route('products.destroy', ['id'=>$products->id]) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-danger">削除</button>
        </form>
   </td>
  </tr>
@endforeach
 </tbody>
</table>
@endsection
