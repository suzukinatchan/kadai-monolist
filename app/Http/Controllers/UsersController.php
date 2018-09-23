<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

//モデルを指定
use App\User;
use App\Item;

class UsersController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //ユーザーがwantした商品をユーザー詳細ページで一覧にしてみられるようにする。
    public function show($id)
    {
        $user = User::find($id);
        $count_want = $user->want_items()->count();
        //検索で出てきたものがitemsテーブルに入り、item_user中間テーブルの中で
        //itemsテーブルのidと中間テーブルのitem_idを結合する
        //次に中間テーブルのuser_idとログインユーザーのidがつながっている
        //ものをitemsテーブルから選択し、20個ごとに表示する。
        $items = \DB::table('items')->join('item_user', 'items.id', '=', 'item_user.item_id')
        ->select('items.*')->where('item_user.user_id', $user->id)->distinct()->paginate(20);

        return view('users.show', [
            'user' => $user,
            'items' => $items,
            'count_want' => $count_want,
            // 'count_have' => $count_have,
        ]);
    }
}
