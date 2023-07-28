<?php
session_start();
$lid = $_POST['lid'];
$lpw = $_POST['lpw'];


//空文字列であれば、trueに
if ($lid === '') {
    $not_lid = true;
}

if ($lpw === '') {
    $not_lpw = true;
}
//もし一つでも空であれば、ログインページにリダイレクト
if ($not_lid || $not_lpw) {
    header('Location: login.php?form_empty=1');

    //後続のコード（ユーザー認証など）が実行されるのを防ぐため
    exit();
}

require_once('../func.php');
$pdo = db_conn();


//2. データ登録SQL作成
$stmt = $pdo->prepare('SELECT * FROM gs_user_table WHERE lid = :lid');
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$status = $stmt->execute();

if(!$status){
    sql_error($stmt);
}

$val = $stmt->fetch();


if ($val['id'] != '' && password_verify($lpw, $val['lpw'])) {
    //login成功時
    $_SESSION['chk_ssid']  = session_id();
    $_SESSION['kanri_flg'] = $val['kanri_flg'];
    $_SESSION['name']      = $val['name'];
    header('Location: index.php');
} else {
    //loginh失敗時
    header('Location: login.php?form_validation=1');
}