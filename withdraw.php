<?php
ini_set('display_errors', 1);

require('./functions.php');
require('./dbConnect.php');
require('./loginCheck.php');

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

<main class="container mt-5">
    <h1 class="page-title text-center my-5 col-8 mx-auto pb-3">退会ページ</h1>
    <section class="profile p-5 mb-5">
        <h4 class="text-center pb-1">本当に退会しますか?</h4>
        <form class="bg-light col-7 mx-auto p-5 text-center" action="" method="post" enctype="multipart/form-data">
        <input class="btn-lg btn-outline-secondary block m-3" type="submit" name="withdraw" value="退会する">
    </form>
    <a class="btn btn-link" href="mypage.php">&lt;戻る</a>
    </section>
</main>

<?php require('footer.php'); ?>