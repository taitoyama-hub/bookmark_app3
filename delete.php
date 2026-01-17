<?php
require_once('funcs.php');

// 管理者チェック処理！（管理者のみ削除可能）
adminCheck();

// 1. GETデータ取得
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// IDが無効な場合は一覧に戻る
if ($id <= 0) {
    header('Location: select.php');
    exit;
}

// 2. DB接続
require_once('db_connect.php');
$pdo = db_connect();

// 3. データ削除SQL作成
$stmt = $pdo->prepare("DELETE FROM gs_kadai3_table WHERE id = :id");

// 4. バインド変数
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

// 5. 実行
$status = $stmt->execute();

// 6. データ削除処理後
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
