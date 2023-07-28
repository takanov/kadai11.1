<?php 
//必ずsession_startは最初に記述
session_start();

//Cookieに保存してある"SessionIDの保存期間を過去にして破棄
//setcookie() はクッキーを設定するPHPの関数です。これにより、名前、値、有効期限、パスなどのクッキーの属性を設定
if (isset($_COOKIE[session_name()])) { //session_name()は、セッションID名を返す関数
    //time()-42000 は、クッキーの新しい有効期限を設定
    setcookie(session_name(), '', time()-42000, '/');
}

//サーバ側での、セッションIDの破棄
session_destroy();

//処理後、index.phpへリダイレクト
header("Location: login.php");