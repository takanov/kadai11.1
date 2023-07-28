<?php
session_start();

require_once('../func.php');
loginCheck();

$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];
$img = '';

//imgがある場合
if (isset($_FILES['img']['name'])) {
    $fileName = $_FILES['img']['name'];
    $img = date('YmdHis') . '_' . $_FILES['img']['name'];
}

//簡単なバリデーション処理
if (trim($_POST['title']) === '') {
    $err[] = 'タイトルを確認してください';
}

if (trim($_POST['content']) === '') {
    $err[] = '内容を確認してください';
}

if (!empty($fileName)) {
    $check = substr($fileName, -3);
    if ($check != 'jpg' && $check != 'gif' && $check != 'png') {
        $err[] = '写真の内容を確認してください';
    }
}

// もしerr配列に何か入っている場合はエラーなので、redirect関数でindexに戻す。その際、GETでerrを渡す。
if (isset($err) && count($err) > 0) {
    redirect('post.php?error=1');
}


/**
 * (1)$_FILES['img']['tmp_name']... 一時的にアップロードされたファイル
 * (2)'../picture/' . $image...写真を保存したい場所。先にフォルダを作成しておく。
 * (3)move_uploaded_fileで、（１）の写真を（２）に移動させる。
 */
if (isset($_FILES['img']['name'])) {
    move_uploaded_file($_FILES['img']['tmp_name'], '../images/' . $img);
}


//　DB接続
$pdo = db_conn();


//データ登録SQLを作成
if (isset($_FILES['img']['name'])) {
    $stmt = $pdo->prepare('UPDATE gs_content_table SET title = :title, content = :content, img = :img, update_time = sysdate() WHERE id = :id');
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':img', $img, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
} else {
    //画像がない場合imgは省略する
    $stmt = $pdo->prepare('UPDATE gs_content_table SET title = :title, content = :content, update_time = sysdate() WHERE id = :id');
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
}

$status = $stmt->execute(); //実行

//データ登録処理後
if (!$status) {
    sql_error($stmt);
} else {
    redirect('index.php');
}
