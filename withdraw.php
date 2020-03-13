<?php

require('./functions.php');
require('./loginAuth.php');

if(!empty($_POST)) {
    try {
        $dbh =  dbConnect();
        $sql = 'UPDATE users SET delete_flg = 1 WHERE id = :id';
        $data = array(':id' => $_SESSION['user_id']);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        if($stmt) {
            $_SESSION = array();
            session_destroy();
            header('Location: login.php');
        }

    } catch(Exception $e) {
        echo '例外エラー発生 : ' . $e->getMessage();
        $err_msg['etc'] = 'しばらくしてから再度試してください';
    }
}
?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>

<main class="container">
    <h1 class="page-title">退会ページ</h1>
        <form class="form text-center my-3" action="" method="post">
            <label class="control-label my-3" for="">本当に退会しますか?</label>
            <div class="text-center">
                <input class="btn-lg btn-danger" type="submit" name="withdraw" value="退会する">
            </div>
        </form>
        <a class="back" href="mypage.php">&lt;戻る</a>
</main>

<?php require('footer.php'); ?>