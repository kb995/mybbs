<?php
require('functions.php');

// セッション削除
$_SESSION = array();
session_destroy();
echo "ログアウトページです";
header('Location: login.php');
?>