<?php

if($_SESSION['login_flg']) {

    // ログイン済みユーザー
    if( ($_SESSION['login_time'] + $_SESSION['login_limit']) < time() ) {
        // ログイン有効期限オーバー
        $_SESSION = array();
        session_destroy();
        header('Location: login.php');
    } else {
        // ログイン有効期限内
        $_SESSION['login_time'] = time();

        if(basename($_SERVER['PHP_SELF']) === 'login.php') {
            header('Location: mypage.php');
        }

    }

} else {
    // 未ログインユーザー
    header('Location: login.php');
}