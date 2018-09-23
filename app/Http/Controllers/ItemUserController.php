<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//使うモデル名を指定（重要）
use App\Item;

class ItemUserController extends Controller
{
    //want()ではWantされた商品をitemCodeから検索して商品をデーターベースに保存し、
    //ログインユーザーと商品の間にwantの関係を保存している。
     public function want()
    {
        //itemCodeは検索した（見た）商品のインスタンスが入った変数
        $itemCode = request()->itemCode;

        // itemCode から商品を検索
        $client = new \RakutenRws_Client();
        $client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
        //$clientは楽天APIのクライアントモジュール
        //executeで機能を実行している。
        $rws_response = $client->execute('IchibaItemSearch', [
            'itemCode' => $itemCode,
        ]);
        //検索の結果出てきた商品を全部$rws_itemの配列の中に入れ込む。
        $rws_item = $rws_response->getData()['Items'][0]['Item'];

        // Item 保存 or 検索（見つかると作成せずにそのインスタンスを取得する）
        //Item::firstOrCreateはモデルのメソッド。検索して見つからないときに作成する。
        $item = Item::firstOrCreate([
            'code' => $rws_item['itemCode'],
            'name' => $rws_item['itemName'],
            'url' => $rws_item['itemUrl'],
            // 画像の URL の最後に ?_ex=128x128 とついてサイズが決められてしまうので取り除く
            'image_url' => str_replace('?_ex=128x128', '', $rws_item['mediumImageUrls'][0]['imageUrl']),
        ]);
        //user()はItemモデルに、want()はUserモデルに入っている。
        \Auth::user()->want($item->id);

        return redirect()->back();
    }
    
    public function dont_want()
    {
        $itemCode = request()->itemCode;

        if (\Auth::user()->is_wanting($itemCode)) {
            $itemId = Item::where('code', $itemCode)->first()->id;
            \Auth::user()->dont_want($itemId);
        }
        return redirect()->back();
    }
    
    public function have()
    {
        $itemCode = request()->itemCode;

        // itemCode から商品を検索
        $client = new \RakutenRws_Client();
        $client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
        $rws_response = $client->execute('IchibaItemSearch', [
            'itemCode' => $itemCode,
        ]);
        $rws_item = $rws_response->getData()['Items'][0]['Item'];

        // Item 保存 or 検索（見つかると作成せずにそのインスタンスを取得する）
        $item = Item::firstOrCreate([
            'code' => $rws_item['itemCode'],
            'name' => $rws_item['itemName'],
            'url' => $rws_item['itemUrl'],
            // 画像の URL の最後に ?_ex=128x128 とついてサイズが決められてしまうので取り除く
            'image_url' => str_replace('?_ex=128x128', '', $rws_item['mediumImageUrls'][0]['imageUrl']),
        ]);

        \Auth::user()->have($item->id);

        return redirect()->back();
    }
    
     public function dont_have()
    {
        $itemCode = request()->itemCode;

        if (\Auth::user()->is_having($itemCode)) {
            $itemId = Item::where('code', $itemCode)->first()->id;
            \Auth::user()->dont_have($itemId);
        }
        return redirect()->back();
    }
    
}
