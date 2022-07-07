@extends('layouts.app')

@section('content')

<h1>商品情報登録画面</h1>

    <!-- form -->
    <form action="store" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
        
        <div class="form-group">
            <label>商品名</label>
            <input type="text" name="product_name" value="" class="form-control">
        </div>
        @if ($errors->first('product_name'))   <!-- ここ追加 -->
        <p class="validation">※{{$errors->first('product_name')}}</p>
        @endif

        <div class="form-group">
        <label>メーカー</label>
        <select class="form-control" name="company_id">
            <option style="display: none;">選択してください</option>
            @foreach ($companies as $company)
            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
            @endforeach
        </select>
        </div>
        @if ($errors->first('company_id'))   <!-- ここ追加 -->
        <p class="validation">※{{$errors->first('company_id')}}</p>
        @endif

        <div class="form-group">
            <label>価格</label>
            <input type="text" name="price" value="" class="form-control">
        </div>
        @if ($errors->first('price'))   <!-- ここ追加 -->
        <p class="validation">※{{$errors->first('price')}}</p>
        @endif

        <div class="form-group">
            <label>在庫数</label>
            <input type="text" name="stock" value="" class="form-control">
        </div>
        @if ($errors->first('stock'))   <!-- ここ追加 -->
        <p class="validation">※{{$errors->first('stock')}}</p>
        @endif

        <div class="form-group">
            <label>コメント</label>
            <textarea name="comment" value="" class="form-control"></textarea>
        </div>


        <div class="form-group">
            <label>商品画像</label>
            <input type="file" name="img_path">
        </div>

        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="row">
          <div class="col-sm-12">
            <input type="submit" value="登録" class="btn btn-primary">
            <a href="products" class="btn btn-primary" style="margin:20px;">戻る</a>
        </div>
    </div>
    </form>
@stop