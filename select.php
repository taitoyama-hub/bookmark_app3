<?php
require_once('funcs.php');

// 検索キーワードを取得（前後の空白を削除）
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

//1.  DB接続します
require_once('db_connect.php');
$pdo = db_connect();
//２．データ取得SQL作成

if ($search !== '')  {
    // 曖昧検索（部分一致検索）：name, url, commentの3カラムを検索
    // % はワイルドカードで、キーワードが含まれていれば検索できる
    $stmt = $pdo->prepare("SELECT * FROM gs_kadai3_table WHERE name LIKE :search OR url LIKE :search OR comment LIKE :search");
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}  else {
    $stmt = $pdo->prepare("SELECT * FROM gs_kadai3_table");
}
$status = $stmt->execute();
//３．データ表示
$view="";
if ($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
    //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
    while ( $result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $view .= '<div class="data-item">';
        $view .= '<div class="data-header">';
        $view .= '<span class="data-date">' . $result['date'] . '</span>';
        $view .= '<span class="data-name">' . h($result['name']) . '</span>';
        $view .= '</div>';
        $view .= '<div class="data-url"><a href="' . h($result['url']) . '" target="_blank" rel="noopener noreferrer">' . h($result['url']) . '</a></div>';
        $view .= '<div class="data-comment">' . nl2br(h($result['comment'])) . '</div>';
        $view .= '<div class="data-actions">';
        $view .= '<a href="edit.php?id=' . h($result['id']) . '" class="action-btn edit-btn"><i class="fas fa-edit"></i> 編集</a>';
        if(isAdmin()): // 管理者のみ削除ボタンを表示
            $view .= '<a href="delete.php?id=' . h($result['id']) . '" class="action-btn delete-btn" onclick="return confirm(\'本当に削除しますか？\')"><i class="fas fa-trash"></i> 削除</a>';
        endif;
        $view .= '</div>';
        $view .= '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📊 ブックマークデータ一覧</title>
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
            <a href="#" class="logo">
                <i class="fas fa-bookmark"></i>
                ブックマーク一覧
            </a>
            <a href="index.php" class="nav-link">
                <i class="fas fa-plus"></i>
                データ登録
            </a>
            <?php
            session_start();
            if(isset($_SESSION['chk_ssid']) && $_SESSION['chk_ssid'] == session_id()): ?>
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    ログアウト
                </a>
            <?php else: ?>
                <a href="login.php" class="nav-link">
                    <i class="fas fa-sign-in-alt"></i>
                    ログイン
                </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="main-container">
        <div class="content-card">
            <h1 class="page-title">📊 ブックマークデータ一覧</h1>
            <p class="page-subtitle">登録されたブックマークの一覧</p>
            
            <form action="select.php" method="get">
                <div class="form-group">
                    <label for="search" class="form-label">検索:</label>
                    <input type="text" id="search" name="search" class="form-input" placeholder="書籍名・URL・コメントで検索" value="<?= h($search) ?>">
                    <button type="submit" class="form-button">検索</button>
                </div>
            </form>


            <div class="data-container">
                <?php if(empty($view)): ?>
                    <!-- もし $view データがない場合の表示 -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <?php if($search !== ''): ?>
                            <p>検索結果が見つかりませんでした</p>
                            <p style="margin-top: 0.5rem; font-size: 0.9rem; color: #999;">
                                別のキーワードで検索してみてください
                            </p>
                        <?php else: ?>
                            <p>まだデータがありません</p>
                            <p style="margin-top: 0.5rem; font-size: 0.9rem; color: #999;">
                                最初のブックマークを登録してみましょう！
                            </p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!-- もし $view データが存在する場合 -->
                    <?= $view ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>