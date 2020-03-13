<?php

require('./functions.php');
require('./validation.php');
require('./loginAuth.php');

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

<main class="container">
    <h1 class="page-title">メッセージ削除</h1>
    <form class="form" action="" method="post">
        <div class="form-group">
            <label class="control-label mb-5" for="">削除内容</label>
            <textarea disabled class="form-control" name="edit" rows="8" cols="40"><?php echo $db_message['message']; ?></textarea>
        </div>
        <div class="text-right">
            <input type="submit" name="submit" class="button" value="削除する">
            <input type="hidden" name="submit">
        </div>
    </form>
</main>

<?php require('footer.php'); ?>