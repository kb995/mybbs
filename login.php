<?php

require('./functions.php');
require('./validation.php');

if(!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // TODO: ログイン時間延長
    // $pass_save = 
    validationRequired($email, 'email');
    validationRequired($password, 'password');

    if(empty($err_msg)) {
        try {
            // emailが一致するデータのpasswordを取得
            $dbh = dbConnect();
            $sql = 'SELECT id, password FROM users WHERE email = :email AND delete_flg = 0';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result) {
                // 取得したデータと入力したパスワードと一致確認
                if($result['password'] == $password) {
                    // DBユーザーIDをSESSIONに保存(ユーザーデータをDBから取得する時のキーに使う)
                    $_SESSION['user_id'] = $result['id'];
                    // ログイン時間を現在に更新
                    $_SESSION['login_time'] = time();
                    // ログイン有効期限を設定(デフォルト１時間)
                    $_SESSION['login_limit'] = $sessionLimit;
                    // ログインフラグ
                    $_SESSION['login_flg'] = true;

                    header('Location: mypage.php');
                } else {
                    $err_msg['etc'] = 'メールアドレスかパスワードが間違っています';
                }
            }else{
                $err_msg['etc'] = 'ログインできませんでした';
            }
        } catch (PDOException $e) {
            echo '例外エラー発生 : ' . $e->getMessage();
            $err_msg['etc'] = 'しばらくしてから再度試してください';
        }
    }
}
?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>
    <!-- main -->
    <main class="container mt-5">
        <h1 class="page-title text-center my-5 col-8 mx-auto pb-3">ログイン</h1>
        <form class="bg-light col-7 mx-auto my-5 p-5" action="" method="post">
            <div class="form-group">
                <label class="control-label" for="">メールドレス</label>
                <input class="form-control" type="email" name="email" value="<?php if(!empty($_POST['email'])) echo h($_POST['email']); ?>">
            </div>
            <p class="err_msg">
                <?php if(!empty($err_msg['email'])) echo $err_msg['email'];  ?>
            </p>
            <div class="form-group">
                <label class="control-label" for="">パスワード</label>
                <input class="form-control" type="password" name="password" value="<?php if(!empty($_POST['password'])) echo h($_POST['password']); ?>">
            </div>
            <p class="err_msg">
                <?php if(!empty($err_msg['password'])) echo $err_msg['password'];  ?>
            </p>
            <p class="err_msg mt-5">
                <?php if(!empty($err_msg['etc'])) echo $err_msg['etc'];  ?>
            </p>
            <div class="text-right">
                <input type="submit" class="btn btn-primary my-5 mr-auto" value="ログインする">
            </div>
        </form>
    </main>
<?php require('footer.php'); ?>