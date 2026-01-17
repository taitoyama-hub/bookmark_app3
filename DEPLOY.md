# デプロイ手順（さくらのレンタルサーバー向け）

## デプロイできない原因

エラー `DBConnectError:SQLSTATE[HY000] [2002] No such file or directory` は、データベース接続情報が本番環境に合わせて設定されていないことが原因です。

## 解決方法

### 1. さくらのサーバー管理画面でデータベース情報を確認

1. さくらのサーバーコントロールパネルにログイン
2. 「データベース」→「MySQL設定」を開く
3. 以下の情報を確認・メモします：
   - **データベース名**: 例 `pathcraft_php03`
   - **データベースユーザー名**: 例 `pathcraft`
   - **データベースパスワード**: 設定したパスワード
   - **データベースホスト**: 通常は `localhost`（確認してください）

### 2. `db_connect.php` を編集

`php03_kadai/db_connect.php` ファイルを開き、以下の部分を実際の値に変更してください：

```php
} else {
    // 本番環境（さくらのレンタルサーバー）の設定
    $db_name = 'YOUR_DB_NAME';      // ← ここにデータベース名を入力
    $db_host = 'localhost';          // ← 通常は 'localhost'（確認してください）
    $user = 'YOUR_DB_USER';          // ← ここにデータベースユーザー名を入力
    $pwd = 'YOUR_DB_PASSWORD';       // ← ここにデータベースパスワードを入力
    
    $dbn = "mysql:dbname={$db_name};charset=utf8;host={$db_host}";
}
```

### 3. データベースとテーブルを作成

さくらのサーバーでデータベースとテーブルを作成する必要があります：

1. さくらのサーバーコントロールパネル → 「データベース」→「phpMyAdmin」を開く
2. 作成したデータベースを選択
3. `gs_kadai3_table.sql` の内容を実行してテーブルを作成

または、phpMyAdminのSQLタブで以下を実行：

```sql
CREATE TABLE `gs_kadai3_table` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `url` text NOT NULL,
  `comment` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### 4. ファイルをアップロード

以下のファイルをさくらのサーバーにアップロードしてください：

- `index.php`
- `insert.php`
- `select.php`
- `edit.php`
- `update.php`
- `delete.php`
- `funcs.php`
- `db_connect.php` ← **重要：接続情報を設定済みのもの**
- `css/style.css`（または `css/` フォルダごと）

### 5. ローカルのデータをさくらサーバーに移行する（オプション）

ローカルのデータベースに保存されているデータをさくらサーバーに移行したい場合：

#### 方法1: phpMyAdminでSQLを実行する（推奨）

1. **ローカルでデータをエクスポート**
   - ローカルのphpMyAdminにアクセス（`http://localhost/phpmyadmin`）
   - データベース `gs_db_kadai3` を選択
   - テーブル `gs_kadai3_table` を選択
   - 「エクスポート」タブを開く
   - 「SQL」形式を選択し、「実行」をクリック
   - ダウンロードしたSQLファイルを開き、`INSERT`文の部分をコピー

2. **さくらサーバーでデータをインポート**
   - さくらのサーバーコントロールパネル → 「データベース」→「phpMyAdmin」を開く
   - 作成したデータベース（例: `pathcraft_php03`）を選択
   - 「SQL」タブを開く
   - コピーした`INSERT`文を貼り付けて実行

#### 方法2: 用意されたSQLファイルを使用

1. `gs_kadai3_table.sql` ファイルを開く
2. さくらのphpMyAdminでデータベースを選択
3. 「SQL」タブを開く
4. `gs_kadai3_table.sql` の内容をコピー＆ペーストして実行

**注意**: 既にデータが存在する場合は、重複を避けるため、先に既存データを確認してください。

### 6. 動作確認

アップロード後、ブラウザで `http://pathcraft.sakura.ne.jp/php03_kadai/index.php` にアクセスして動作を確認してください。

## 注意事項

- `db_connect.php` には機密情報（パスワード）が含まれます。GitHubなどに公開しないよう注意してください
- さくらのレンタルサーバーでは、データベース名の前にユーザー名が自動的に付加される場合があります（例: `ユーザー名_データベース名`）

## トラブルシューティング

### まだエラーが出る場合

1. **データベース名の確認**: さくらの場合、データベース名の前にユーザー名が付くことがあります
2. **ホスト名の確認**: 通常は `localhost` ですが、サーバー管理画面で確認してください
3. **パスワードの確認**: パスワードに特殊文字が含まれている場合、エスケープが必要な場合があります
4. **テーブルの存在確認**: phpMyAdminでテーブル `gs_kadai3_table` が存在するか確認してください
