<?php
$err_msg = array();

// 空チェック
// 半角チェック
// email重複チェック
// 最大文字数チェック
// 最小文字数チェック

// emailチェック
// passwordチェック
// 名前チェック


// 空チェック
function validationRequired($str, $key) {
    if($str === '') {
        global $err_msg;
        $err_msg[$key] = '※ 入力されていません';
    }
}
// 半角チェック
// TODO 内容確認
function validationHalfsize($str, $key) {
    if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = '半角英数字で入力して下さい';
    }
}
function validationEmailType($str, $key) {
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
        global $err_msg;
        $err_msg[$key] = 'Emailの形式で入力して下さい';
    }
}
// 最大文字数チェック
function validationMax($str, $key, $max = 255) {
    if(mb_strlen($str) > $max){
        global $err_msg;
        $err_msg[$key] = "{$max}文字以内で入力して下さい";
    }
}
// 最小文字数チェック
function validationMin($str, $key, $min = 6) {
    if(mb_strlen($str) < $min){
        global $err_msg;
        $err_msg[$key] = "6文字以上で入力して下さい";
    }
}
// 画像タイプチェック
function validationImgType($img, $key) {
    if(!empty($img)) {
        $ext = substr($img, -3);
        if($ext != 'jpg' && $ext != 'peg' && $ext != 'png' && $ext != 'gif') {
            global $err_msg;
            $err_msg[$key] = '画像は「jpg」「jpeg」「png」「gif」の形式のみ使用できます';
        }
    }
}
// パスワード一致確認
function passMatch($pass, $pass_re, $key) {
    if($pass !== $pass_re) {
        global $err_msg;
        $err_msg[$key] = 'パスワードとパスワード(再)が一致していません';
    }
}
// 新規登録アカウント重複チェック
function emailDuplication($email) {
    global $err_msg;
    try {
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM users WHERE email = :email';
        $data = array(':email' => $email);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<pre>"; var_dump($result['count(*)']); echo"</pre>";
        if($result['count(*)'] != 0) {
            $err_msg['email'] = 'そのアドレスは既に登録されています';
        }
    } catch (Exception $e) {
        echo '例外エラー発生 : ' . $e->getMessage();
        $err_msg['etc'] = 'しばらくしてから再度試してください';
    }
}

// Emailチェックまとめ
function emailCheck($str, $key) {
    validationEmailType($str, $key);
    validationMax($str, $key);
    validationMin($str, $key);
}
// パスワードチェックまとめ
function passCheck($str, $key) {
    validationHalfsize($str, $key);
    validationMax($str, $key);
    validationMin($str, $key);
}