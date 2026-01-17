<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 ログイン - ブックマークアプリ</title>
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
            <h1 class="form-title">🔐 ログイン</h1>
            <p class="form-subtitle">ブックマークアプリにログインしてください</p>
            
            <form name="form1" action="login_act.php" method="post">
                <div class="form-group">
                    <label for="lid" class="form-label">
                        <i class="fas fa-user"></i> ID
                    </label>
                    <input type="text" id="lid" name="lid" class="form-input" placeholder="ユーザーIDを入力してください" required>
                </div>

                <div class="form-group">
                    <label for="lpw" class="form-label">
                        <i class="fas fa-lock"></i> パスワード
                    </label>
                    <input type="password" id="lpw" name="lpw" class="form-input" placeholder="パスワードを入力してください" required>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    ログイン
                </button>
            </form>
        </div>
    </main>
</body>

</html>
