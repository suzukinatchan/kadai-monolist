<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
     //wantとhave両方の関係のItem一覧を取得するメソッド
     public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('type')->withTimestamps();
    }
    
    
    //userがwantしているitem一覧をあらわすためのメソッド
    public function want_items()
    {
        return $this->items()->where('type', 'want');
    }

    //wantした時に中間テーブルにレコードを保存する。
    public function want($itemId)
    {
        // 既に Want しているかの確認
        $exist = $this->is_wanting($itemId);

        if ($exist) {
            // 既に Want していれば何もしない
            return false;
        } else {
            // 未 Want であれば Want する
            //wantかhaveかタイプを指定する必要がある。
            $this->items()->attach($itemId, ['type' => 'want']);
            return true;
        }
    }
    
    //
    public function dont_want($itemId)
    {
        // 既に Want しているかの確認
        $exist = $this->is_wanting($itemId);

        if ($exist) {
            // 既に Want していれば Want を外す
            //detachは使わなくてもmysqlのコードを直接書けば行ける。
            //detachはtypeを絞り込んで削除することができないので直接SQLをコーディングしている。
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'want'", [$this->id, $itemId]);
        } else {
            // 未 Want であれば何もしない
            return false;
        }
    }
    
    //is_numeric()は'1234'という文字列型の整数でも整数の数字だと判断する。
    public function is_wanting($itemIdOrCode)
    {
        if (is_numeric($itemIdOrCode)) {
            $item_id_exists = $this->want_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
        } else {
            $item_code_exists = $this->want_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
        }
    }
    
    
    //userがhaveしているitem一覧をあらわすためのメソッド
    public function have_items()
    {
        return $this->items()->where('type', 'have');
    }

    //haveした時に中間テーブルにレコードを保存する。
    public function have($itemId)
    {
        // 既に have しているかの確認
        $exist = $this->is_having($itemId);

        if ($exist) {
            // 既に have していれば何もしない
            return false;
        } else {
            // 未 have であればhave する
            //wantかhaveかタイプを指定する必要がある。
            $this->items()->attach($itemId, ['type' => 'have']);
            return true;
        }
    }
    
    //
    public function dont_have($itemId)
    {
        // 既に have しているかの確認
        $exist = $this->is_having($itemId);

        if ($exist) {
            // 既に have していれば have を外す
            //detachは使わなくてもmysqlのコードを直接書けば行ける。
            //detachはtypeを絞り込んで削除することができないので直接SQLをコーディングしている。
            //[$this->id, $itemId]で指定したuser_id,item_idを”？”の中に代入して中間テーブル内のレコードを指定している。
            //$itemIdという仮引数は、メソッドの（）内に代入されたitem_idという名の数字が入るように変数化されている。
            //item_idは中間テーブル用に作られた、itemにつけられたカラム(id）のこと。
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'have'", [$this->id, $itemId]);
        } else {
            // 未 have であれば何もしない
            return false;
        }
    }
    
    //is_numeric()は'1234'という文字列型の整数でも整数の数字だと判断する。
    //$ItemIdOrCodeという仮引数はitem_idだったり、そのアイテムのコードだったりする。
    //だから、整数ではない場合があるらしい
    public function is_having($itemIdOrCode)
    {
        if (is_numeric($itemIdOrCode)) {
            $item_id_exists = $this->have_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
        } else {
            $item_code_exists = $this->have_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
        }
    }
    
    
    
    
}

