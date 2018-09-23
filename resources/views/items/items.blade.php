<!--
商品の検索結果で、crate.blade.phpに組み込まれる。
そう、商品の検索結果！！！foreachで一覧を並べている。
商品の表示はItemModelのインスタンスを利用して表示する。
これは、後ほどItemとして商品を保存した後に再利用するためである。
$itemsは、ItemsControllerで出てきた、商品の検索結果が入っている変数である。
-->
@if ($items)
    <div class="row">
        @foreach ($items as $item)
            <div class="item">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <img src="{{ $item->image_url }}" alt="">
                        </div>
                        <div class="panel-body">
                            <p class="item-title"><a href="#">{{ $item->name }}</a></p>
                            <!--ボタンを追加-->
                            <div class="buttons text-center">
                                @if(Auth::check())
                                    @include('items.want_button', ['item' => $item])
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif