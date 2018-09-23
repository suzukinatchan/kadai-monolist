<!--
商品の検索結果で、crate.blade.phpに組み込まれる。
そう、商品の検索結果！！！foreachで一覧を並べている。
商品の表示はItemModelのインスタンスを利用して表示する。
これは、後ほどItemとして商品を保存した後に再利用するためである。
$itemsは、ItemsControllerで出てきた、商品の検索結果が入っている変数である。
$itemsには一度検索されたアイテムがすべてテーブルに入っており、それを全部見せてくれる。
-->
@if ($items)
    <div class="row">
        @foreach ($items as $item)
            <div class="item">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <img src="{{ $item->image_url }}" alt="" class="">
                        </div>
                        <div class="panel-body">
                            @if ($item->id)
                                <p class="item-title"><a href="{{ route('items.show', $item->id) }}">{{ $item->name }}</a></p>
                            @else
                                <p class="item-title">{{ $item->name }}</p>
                            @endif
                            <div class="buttons text-center">
                                @if (Auth::check())
                                    @include('items.want_button', ['item' => $item])
                                    @include('items.have_button', ['item' => $item])
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif