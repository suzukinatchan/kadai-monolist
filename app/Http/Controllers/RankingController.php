<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//使うモデルを指定
use App\Item;

class RankingController extends Controller
{
    public function want()
    {
        //中間テーブルItem_userを指定（FROM item_user)
        $items = \DB::table('item_user')
        //itemテーブルitをem_userテーブルと結合している
        ->join('items', 'item_user.item_id', '=', 'items.id')
        //取得したいテーブルのカラムを選択。'COUNT(*) as count'で集計している。
        //計算によって一時的に出現するカラムにcountという名前を付けている。
        ->select('items.*', \DB::raw('COUNT(*) as want_ranking_count'))
        //typeがwantとなるもので、item_idが重複するレコード同士をカウントする。
        ->where('type', 'want')
        //ユーザーがwantした商品を、すべてのカラムを使ってグループ化している。
        ->groupBy('items.id', 'items.code', 'items.name', 'items.url',
        'items.image_url','items.created_at', 'items.updated_at')
        //カラム名count（重複するitem_idを集計した数）を数が大きい順に並べ替える。
        ->orderBy('want_ranking_count', 'DESC')
        //上位10個まで表示する。
        ->take(10)
        ->get();

        return view('ranking.want', [
            'items' => $items,
        ]);
    }
    
    public function have()
    {
        $items = \DB::table('item_user')
        ->join('items', 'item_user.item_id', '=', 'items.id')
        ->select('items.*', \DB::raw('COUNT(*) as have_ranking_count'))
        ->where('type', 'have')
        ->groupBy('items.id', 'items.code', 'items.name', 'items.url', 
        'items.image_url','items.created_at', 'items.updated_at')
        ->orderBy('have_ranking_count', 'DESC')
        ->take(10)
        ->get();

        return view('ranking.have', [
            'items' => $items,
        ]);
    }
}
