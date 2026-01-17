<?php
require_once('funcs.php');

// ログインチェック処理！
loginCheck();

// 1. POSTデータ取得
$id = $_POST['id'];
$name = $_POST['name'];
$url = $_POST['url'];
$comment = $_POST['comment'];

// 2. DB接続
require_once('db_connect.php');
$pdo = db_connect();

// 3. データ更新SQL作成
$stmt = $pdo->prepare("UPDATE gs_kadai3_table SET name = :name, url = :url, comment = :comment, date = now() WHERE id = :id");

// 4. バインド変数
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':url', $url, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);

// 5. 実行
$status = $stmt->execute();

// 6. データ更新処理後
if ($status === false) {
    // SQL実行時にエラーがある場合
    $error = $stmt->errorInfo();
    exit('ErrorMessage:' . $error[2]);
} else {
    // select.phpへリダイレクト
    header('Location: select.php');
    exit();
}
?>
