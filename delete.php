<?php

require('./functions.php');
require('./validation.php');
require('./loginAuth.php');

// $db_user = getDbUser($_SESSION['user_id']);

// データベースのメッセージデータ
$db_message = getOneMessage($_GET['message_id']);

// メッセージ編集処理
if(!empty($_POST)) {
     
    try {
        $dbh = dbConnect();
        $sql = 'UPDATE message SET delete_flg = 1 WHERE id = :message_id';
        $data = array(':message_id' => $_GET['message_id']);
        $stmt = $dbh->prepare($sql);
        $result = $stmt->execute($data);
        echo "<pre>"; var_dump($result); echo"</pre>";
        header("Location: bord.php");
    } catch (PDOException $e) {
        echo '例外エラー発生 : ' . $e->getMessage();
        $err_msg['etc'] = 'しばらくしてから再度試してください';
    }
}



?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>

<main class="container mt-5">
    <h1 class="page-title text-center my-5 col-8 mx-auto pb-3">メッセージ削除</h1>
    <section class="p-5 mb-5">
    <form action="" method="post">
        <div class="form-group mb-5">
            <label class="control-label h4 pb-2" for="">削除内容</label>
            <textarea disabled class="form-control" name="edit" rows="8" cols="40"><?php echo $db_message['message']; ?></textarea>
        </div>
        <div class="text-center">
            <input type="submit" name="submit" class="btn-block w-20 btn-primary my-5 p-2  mr-auto" value="削除する">
            <input type="hidden" name="submit">
        </div>
    </form>
    </section>
</main>

<?php require('footer.php'); ?>