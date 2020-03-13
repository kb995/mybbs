<?php

require('./functions.php');
require('./validation.php');
require('./loginAuth.php');

$db_user = getDbUser($_SESSION['user_id']);
$default_img = 'default.jpeg';

// データベースのメッセージデータ
$db_message = getOneMessage($_GET['message_id']);

// メッセージ編集処理
if(!empty($_POST)) {
    $edit = $_POST['edit'];

    // バリデーション
    validationRequired($edit, 'edit');
    validationMax($edit, 'edit', 100);

    if(empty($err_msg)) {
        try {
            $dbh = dbConnect();
            $sql = 'UPDATE message SET message = :message WHERE id = :message_id';
            $data = array(':message' => $edit, ':message_id' => $_GET['message_id']);
            $stmt = $dbh->prepare($sql);
            $result = $stmt->execute($data);
            header("Location: bord.php");
        } catch (PDOException $e) {
            echo '例外エラー発生 : ' . $e->getMessage();
            $err_msg['etc'] = 'しばらくしてから再度試してください';
        }
    }
}


?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>

<main class="container">
    <h1 class="page-title">メッセージ編集</h1>
    <form class="form" action="" method="post">
        <div class="form-group text-center">
            <label class="control-label" for="">編集内容</label>
            <textarea class="form-control" name="edit" rows="8" cols="40"><?php echo $db_message['message']; ?></textarea>
            <p class="err_msg">
                <?php if(!empty($err_msg['edit'])) echo $err_msg['edit'];  ?>
            </p>
        </div>
        <div class="text-center">
            <input type="submit" class="button" value="編集する">
        </div>
        <a class="text-left back-link" href="bord.php">&lt;戻る</a>
    </form>
</main>

<?php require('footer.php'); ?>

<?php


?>