<?php
//共通に使う関数を記述
//XSS対応（ echoする場所で使用！それ以外はNG ）

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES);
}

//SQLエラー
function sql_error($stmt)
{
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit('SQLError:' . $error[2]);
}

// ログインチェク処理 loginCheck()
function loginCheck()
{
    session_start();
    if(!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] != session_id()){
        exit("LOGIN ERRORだよ");
    }
    session_regenerate_id(true);
    $_SESSION['chk_ssid'] = session_id();
}

// ログイン状態をチェック（表示用）
function isLogin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['chk_ssid']) && $_SESSION['chk_ssid'] == session_id();
}

// 管理者チェック
function isAdmin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['chk_ssid']) && $_SESSION['chk_ssid'] == session_id() && isset($_SESSION['kanri_flg']) && $_SESSION['kanri_flg'] == 1;
}

// 管理者チェック（必須）
function adminCheck()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] != session_id()){
        exit("LOGIN ERRORだよ");
    }
    if(!isset($_SESSION['kanri_flg']) || $_SESSION['kanri_flg'] != 1){
        exit("管理者のみアクセス可能です");
    }
    session_regenerate_id(true);
    $_SESSION['chk_ssid'] = session_id();
}