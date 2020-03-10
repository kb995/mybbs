<?php

require('./functions.php');
require('./validation.php');
require('./loginAuth.php');


// DBからユーザーデータを取得
$db_user = getDbUser($_SESSION['user_id']);
$default_img = 'default.jpeg';
// ページング
if(isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
} else {
    $page = 1;
}
$start = 5 * ($page - 1);
$messages = getUsersMessage($start, 5);

// POSTからのメッセージ投稿保存処理
if(!empty($_POST)) {
    $message = $_POST['message'];

    // バリデーションチェック
    validationRequired($message, 'message');
    validationMax($message, 'message', 100);
    // メッセージ投稿処理
    if(empty($err_msg)) {
        try {
            $dbh = dbConnect();
            $sql = 'INSERT INTO message (message, user_id, modify_time, create_date) VALUE (:message, :u_id, :m_time, :c_date)';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':message', $message, PDO::PARAM_STR);
            $stmt->bindValue(':u_id', $_SESSION['user_id'], PDO::PARAM_STR);
            $stmt->bindValue(':m_time', date('Y-m-d H:i:s'));
            $stmt->bindValue(':c_date', date('Y-m-d H:i:s'));
            $result = $stmt->execute();
        } catch (PDOException $e) {
            echo '例外エラー発生 : ' . $e->getMessage();
            $err_msg['etc'] = 'しばらくしてから再度試してください';
        }
        header('Location: bord.php');
    }
}


?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>

<main class="container mt-5">
    <h1 class="page-title text-center my-5 col-8 mx-auto pb-3">みんなのひとりごと</h1>
    <form class="bg-light col-7 mx-auto p-5" action="" method="post">
        <div class="bg-light rounded">
            <p class="pl-5 h5 block"><?php echo $db_user['user_name']; ?></p>
            <?php if(!empty($db_user['thumbnail'])): ?>
                <img style="width:150px;height:100px" class="m-2 mb-5 bg-white rounded block" src="<?php echo 'img/' . $db_user['thumbnail']; ?>" alt="あなたのプロフィール画像">
            <?php else: ?>
                <img style="width:150px; height:150px;margin-top: 20px;" src="<?php echo 'img/' . $default_img; ?>" alt="デフォルト画像">
            <?php endif; ?>
        </div>
        <div class="form-group mb-5">
            <label class="control-label h4 pb-2" for="">ひとりごとをつぶやく</label>
            <textarea class="form-control" name="message" rows="8" cols="40">
            </textarea>
            <p class="err_msg">
                <?php if(!empty($err_msg['message'])) echo $err_msg['message'];  ?>
            </p>
        </div>
        <div class="text-center">
            <input type="submit" class="btn-block w-20 btn-primary my-5 p-2  mr-auto" value="投稿する">
        </div>
    </form>

    <!-- メッセージ表示領域 -->
    <section style="width:70%; min-height: 1000px;" class="mx-auto my-5 p-5">
        <?php foreach($messages as $message): ?>
            <div style="overflow:hidden; width: 150px; height: 150px; background-color: white;">
                <?php if(!empty($message['thumbnail'])): ?>
                    <a href="profDetail.php?user_id=<?php echo $message['user_id']; ?>">
                        <img class="block" style="width: 100%; height:auto;" src="<?php echo 'img/' . $message['thumbnail']; ?>" alt="あなたのプロフィール画像">
                    </a>
                <?php else: ?>
                    <img style="width:100px;height:100px;" src="<?php echo 'img/' . $default_img; ?>" alt="デフォルト画像">
                <?php endif; ?>
            </div>
            <p class="p-2"><?php echo $message['user_name']; ?></p>
            <span class="pr-3"><?php echo $message['create_date']; ?></span>
            <?php if($_SESSION['user_id'] == $message['user_id']): ?>
                <a href="delete.php?message_id=<?php echo $message['id']; ?>">削除</a>
                <a href="editMessage.php?message_id=<?php echo $message['id']; ?>" class="pr-3">編集</a>
            <?php endif; ?>
            <p class="mb-5 mt-3 p-3" style="border-radius: 8px; background-color: #f1f1f1; color: #555;"><?php echo $message['message']; ?></p>

        <?php endforeach; ?>
        <!-- ページング -->
        <?php if($page >= 2): ?>
        <a class="pl-5" href="bord.php?page=<?php echo $page - 1; ?>">&lt;<?php echo $page - 1; ?>ページ目</a>
        <?php endif; ?>
        <?php
        $dbh = dbConnect();
        $stmt = $dbh->query('SELECT COUNT(*) FROM message WHERE delete_flg = 0');
        $page_count = $stmt->fetch(PDO::FETCH_ASSOC);
        $max_page = ceil($page_count['COUNT(*)'] / 5);
        if($page < $max_page):
        ?>
        <a class="pl-5" href="bord.php?page=<?php echo $page + 1; ?>"><?php echo $page + 1; ?>ページ目&gt;</a>
        <?php endif; ?>
    </section>
</main>

<?php require('footer.php'); ?>