<!--ボタンのビュー
items.blade.phpに組み込まれる-->
@if (Auth::user()->is_wanting($item->code))
    {!! Form::open(['route' => 'item_user.dont_want', 'method' => 'delete']) !!}
        <!--　$item->codeはItemUserControllerのrequest()->itemCodeとして取得される
        商品のItemCodeがわからないとどの商品がWantされたかわからないので
        hidden()でitemCodeを渡している-->
        {!! Form::hidden('itemCode', $item->code) !!}
        {!! Form::submit('Want', ['class' => 'btn btn-success']) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(['route' => 'item_user.want']) !!}
        {!! Form::hidden('itemCode', $item->code) !!}
        {!! Form::submit('Want it', ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endif