<?php
ini_set('display_errors', 1);

require('./functions.php');
require('./validation.php');
require('./dbConnect.php');
require('./loginCheck.php');

$db_user = getDbUser($_SESSION['user_id']);
$default_img = 'default.jpeg';

// データベースのメッセージデータ
$db_message = getOneMessage($_GET['message_id']);
// echo "<pre>"; var_dump($db_message); echo"</pre>";

// メッセージ編集処理
if(!empty($_POST)) {
    // ポスト内容
    $edit = $_POST['edit'];
    // 返信するフラグあればメッセージidを入れる
    $reply_id = '1';
    // バリデーションチェック
    validationRequired($edit, 'edit');
    validationMax($edit, 'edit', 100);

    // ====================================================
    // 編集処理
    // ====================================================

    if(empty($err_msg)) {
        try {
            $dbh = dbConnect();
            $sql = 'UPDATE message SET message = :message WHERE id = :message_id';
            $data = array(':message' => $edit, ':message_id' => $_GET['message_id']);
            $stmt = $dbh->prepare($sql);
            $result = $stmt->execute($data);
            header("Location: bord.php");
        } catch (Exception $e) {
            echo '例外エラー発生 : ' . $e->getMessage();
            $err_msg['etc'] = 'しばらくしてから再度試してください';
        }
    }
}


?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>

<main class="container mt-5">
    <h1 class="page-title text-center my-5 col-8 mx-auto pb-3">メッセージ編集</h1>
    <section class="p-5 mb-5">
    <form action="" method="post">
        <div class="form-group mb-5">
            <label class="control-label h4 pb-2" for="">編集内容</label>
            <textarea class="form-control" name="edit" rows="8" cols="40"><?php echo $db_message['message']; ?></textarea>
            <p class="err_msg">
                <?php if(!empty($err_msg['edit'])) echo $err_msg['edit'];  ?>
            </p>
        </div>
        <div class="text-center">
            <input type="submit" class="btn-block w-20 btn-primary my-5 p-2  mr-auto" value="編集する">
        </div>
    </form>
    </section>
</main>

<?php require('footer.php'); ?>

<?php


?>