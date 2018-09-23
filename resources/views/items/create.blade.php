<!--
上：商品検索フォーム
下：検索結果を表示するためのページ
-->
@extends('layouts.app')

@section('content')
    <div class="search">
        <div class="row">
            <div class="text-center">
                <!--method=getなので、このフォームはitemsController@createに送信する（GETメソッドによってフォームが送信されている！）-->
                {!! Form::open(['route' => 'items.create', 'method' => 'get', 'class' => 'form-inline']) !!}
                    <div class="form-group">
                        <!--第一引数に検索キーワードが入るので、
                        ItemsControllerではrequest->keyword()で検索キーワードが取得できる-->
                        {!! Form::text('keyword', $keyword, ['class' => 'form-control input-lg', 'placeholder' => 'キーワードを入力', 'size' => 40]) !!}
                    </div>
                    {!! Form::submit('商品を検索', ['class' => 'btn btn-success btn-lg']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    @include('items.items', ['items' => $items])
@endsection