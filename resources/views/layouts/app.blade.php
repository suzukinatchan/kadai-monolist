<!--共通レイアウト
・ビューポート
・IE設定
・bootstrapの読み込み
・スタイルシートの読み込み
・ページタブに出てくる名前をタイトルタグでつける
・ナビバーの読み込み
・エラー時の画面処理blade読み込み
・yieldでcover,contentの読み込み
-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!--ページタブ上に表示される名前-->
        <title>Monolist</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        <!--{{ secure_asset('css/style.css') }}によってドメイン直下
        を指し示してくれる。-->
        <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">
    </head>
    <body>
        @include('commons.navbar')
        <!--これはいったい何なのか-->
        @yield('cover')

        <div class="container">
            @include('commons.error_messages')
            @yield('content')
        </div>

        @include('commons.footer')
    </body>
</html>