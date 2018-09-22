<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemsController extends Controller
{
     public function create()
    {
        //フォームから送信される検索ワードを取得
        //ViewでForm::text('keyword')というinputが設置されるので、これを受け取る。
        $keyword = request()->keyword;
        //$itemsを空の配列として初期化する、後々検索ワードが入力された時に値がここに入る。
        //初期化しないとview側で$itemsにアクセスしたときにnullとなってしまいエラーとなる。
        $items = [];
        //検索ワードの中でkeywordを与えて、楽天APIを使って検索が行われる。
        if ($keyword) {
            //インスタンスを$clientに代入
            $client = new \RakutenRws_Client();
            //インスタンスの中にアプリIDを設定した。
            $client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
            //オプションをつけて検索実行
            //検索キーワード・画像があるものを20件検索するオプションをつける
            $rws_response = $client->execute('IchibaItemSearch', [
                'keyword' => $keyword,
                'imageFlag' => 1,
                'hits' => 20,
            ]);

            // 扱い易いように Item としてインスタンスを作成する（保存はしない）
            //$rws_responseを直接扱うより扱いやすいためにforeach文にする。
            //後ほど、want,haveしたものだけを保存するようにするので、
            //すべての検索結果を保存するわけではない。
            foreach ($rws_response->getData()['Items'] as $rws_item) {
                $item = new Item();
                $item->code = $rws_item['Item']['itemCode'];
                $item->name = $rws_item['Item']['itemName'];
                $item->url = $rws_item['Item']['itemUrl'];
                //画像末尾に含まれる'?_ex=128x128'(画像サイズを128×128にする、の意)
                //を削除している。このままだと小さすぎるためである。
                //str_replace()は第三引数から第一引数を見つけ出し第二引数に置換する関数。
                //今回は’’（空文字）なので見つけたら削除される。
                $item->image_url = str_replace('?_ex=128x128', '', $rws_item['Item']['mediumImageUrls'][0]['imageUrl']);
                //最初に初期化していた$itemsに追加されていく。（上書きではなく追加されていっている）
                $items[] = $item;
            }
        }

        return view('items.create', [
            'keyword' => $keyword,
            'items' => $items,
        ]);
    }
}
