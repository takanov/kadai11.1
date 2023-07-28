<?php
session_start();
require_once('func.php');
require_once('common/footer.php');
require_once('common/head_parts.php');

//データベース接続
$pdo = db_conn();

//SQLクエリを準備
$stmt = $pdo->prepare('SELECT * FROM gs_content_table');

//executeメソッドを呼び出して、準備したSQLクエリを実行
//クエリの実行に成功したかどうかを示すブール値を返し、$status変数に保存
$status = $stmt->execute();


//クエリの成功のチェックし、結果の取得
//空の文字列は取得データを表示するために利用
$view = '';
if ($status == false) {
    sql_error($stmt);
} else {
    //結果を連想配列として取得して、変数に保存
    //連想配列は、列の名前をキーとする配列で、これにより結果セット内の各行をより直感的にアクセス
    $contents = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <?= head_parts('ブログ画面') ?>
</head>

<body id="main">
    <div class="album py-5 bg-light">
        <figure class="text-center">
            <h1>testブログ</h1>
        </figure>
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($contents as $content): ?>
                <!-- <a href="#"> -->
                    <div class="col">
                        <div class="card shadow-sm">
                            <?php if($content['img']) : ?>
                            <!-- 画像が登録されている場合は↓ -->
                            <img src="images/<?= $content['img'] ?>" alt="" class="bd-placeholder-img card-img-top" >
                            <?php else : ?>
                            <img src="images/default_image/no_image_logo.png" alt="" class="bd-placeholder-img card-img-top" >
                            <?php endif  ?>
                            <div class="card-body">
                                <h3><?= $content['title'] ?></h3>
                                <p class="card-text"><?=nl2br($content['content'])?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">登録日:<?= $content['date'] ?></small>
                                </div>
                                <?php if (!is_null($content['update_time'])): ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">更新日:<?= $content['update_time'] ?></small>
                                </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                <!-- </a> -->
                <?php endforeach ?>
            </div>
        </div>
    </div>
<?= $footer ?>
</body>
</html>
