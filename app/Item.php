<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['code', 'name', 'url', 'image_url'];
    
    //wantとhave両方の関係のUser一覧を取得するメソッド
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('type')->withTimestamps();
    }

    //wantのみのUser一覧を取得するためのメソッド
    public function want_users()
    {
        return $this->users()->where('type', 'want');
    }
    //haveしたUser一覧を取得するためのメソッド
    public function have_users()
    {
        return $this->users()->where('type', 'have');
    }
}
