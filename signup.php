<?php
ini_set('display_errors', 1);

require('./functions.php');
require('./validation.php');
require('./dbConnect.php');


if(!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_re = $_POST['password_re'];

    validationRequired($email, 'email');
    validationRequired($password, 'password');
    validationRequired($password_re, 'password_re');

    if(empty($err_msg)) {
        // バリデーションチェック
        emailCheck($email, 'email');
        passCheck($password, 'password');
        passMatch($password, $password_re, 'password_re');
        emailDuplication($email);

        if(empty($err_msg)) {
            try {
                $dbh = dbConnect();
                $sql = 'INSERT INTO users (email, password, modify_time, create_date) VALUE (:email, :password, :modify_time, :create_date)';
                $data = array(':email' => $email, ':password' => $password, ':modify_time' => date('Y-m-d H:i:s'), ':create_date' => date('Y-m-d H:i:s'));
                $stmt = $dbh->prepare($sql);
                $result = $stmt->execute($data);
                if($result) {
                    // TODO ログインページは挟まない
                    header('Location: mypage.php');
                }
            } catch (Exception $e) {
                echo '例外エラー発生 : ' . $e->getMessage();
                $err_msg['etc'] = 'しばらくしてから再度試してください';
            }
        }
    }
}
?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>
    <!-- main -->
    <main class="container mt-5">
        <h1 class="page-title text-center my-5 col-8 mx-auto pb-3">新規登録</h1>
        <form class="bg-light col-7 mx-auto p-5" action="" method="post">
            <div class="form-group">
                <label class="control-label" for="">メールアドレス<span class="badge badge-danger ml-2">必須</span></label>
                <input class="form-control" type="email" name="email" value="<?php if(!empty($_POST['email'])) echo h($_POST['email']); ?>">
                <p class="err_msg">
                    <?php if(!empty($err_msg['email'])) echo $err_msg['email'];  ?>
                </p>
            </div>
            <div class="form-group">
                <label class="control-label" for="">パスワード<span class="badge badge-danger ml-2">必須</span></label>
                <input class="form-control" type="password" name="password" value="<?php if(!empty($_POST['password'])) echo h($_POST['password']); ?>">
                <p class="err_msg">
                    <?php if(!empty($err_msg['password'])) echo $err_msg['password'];  ?>
                </p>
            </div>
            <div class="form-group">
                <label class="control-label" for="">パスワード(再)<span class="badge badge-danger ml-2">必須</span></label>
                <input class="form-control" type="password" name="password_re" value="<?php if(!empty($_POST['password_re'])) echo h($_POST['password_re']); ?>">
                <p class="err_msg">
                    <?php if(!empty($err_msg['password_re'])) echo $err_msg['password_re'];  ?>
                </p>
                <p class="err_msg mt-5">
                    <?php if(!empty($err_msg['etc'])) echo $err_msg['etc'];  ?>
                </p>
            </div>
            <div class="text-right my-5">
                <input type="submit" class="btn btn-primary mr-auto" value="登録する">
            </div>
        </form>
    </main>
<?php require('footer.php'); ?>