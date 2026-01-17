<?php
require_once('funcs.php');

// ログインチェック処理！
loginCheck();

// IDを取得
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// IDが無効な場合は一覧に戻る
if ($id <= 0) {
    header('Location: select.php');
    exit;
}

// DB接続
require_once('db_connect.php');
$pdo = db_connect();

// データ取得
$stmt = $pdo->prepare("SELECT * FROM gs_kadai3_table WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status === false) {
    $error = $stmt->errorInfo();
    exit('ErrorMessage:' . $error[2]);
}

$result = $stmt->fetch(PDO::FETCH_ASSOC);

// データが見つからない場合は一覧に戻る
if (!$result) {
    header('Location: select.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編集 - ブックマークアプリ</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- 装飾要素 -->
    <div class="decoration"></div>
    <div class="decoration"></div>

    <!-- ヘッダー -->
    <header class="header">
        <div class="nav-container">
            <a href="select.php" class="logo">
                <i class="fas fa-clipboard-list"></i>
                ブックマークアプリ
            </a>
            <a href="select.php" class="nav-link">
                <i class="fas fa-list"></i>
                データ一覧
            </a>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="main-container form-page">
        <div class="form-card">
            <h1 class="form-title">編集</h1>
            <p class="form-subtitle">ブックマーク情報を編集してください</p>

            <form method="post" action="update.php">
                <input type="hidden" name="id" value="<?= h($result['id']) ?>">

                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-user"></i> 書籍名
                    </label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="例：PHP入門" value="<?= h($result['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="url" class="form-label">
                        <i class="fas fa-link"></i> 書籍URL
                    </label>
                    <input type="url" id="url" name="url" class="form-input" placeholder="例：https://example.com" value="<?= h($result['url']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="comment" class="form-label">
                        <i class="fas fa-comment"></i> 書籍コメント
                    </label>
                    <textarea id="comment" name="comment" class="form-textarea" placeholder="書籍についてのコメントをお書きください..." required><?= h($result['comment']) ?></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i>
                        更新する
                    </button>
                    <a href="select.php" class="cancel-btn">
                        <i class="fas fa-times"></i>
                        キャンセル
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
