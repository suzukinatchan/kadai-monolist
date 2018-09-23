<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//モデルを指定
use App\Item;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    //wantしたItem一覧の表示
    public function index()
    {
        $items = Item::orderBy('updated_at', 'desc')->paginate(20);
        return view('welcome',['items'=>$items,
        ]);
    }
}
