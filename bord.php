<?php
ini_set('display_errors', 1);

require('./functions.php');
require('./validation.php');
require('./dbConnect.php');
require('./loginCheck.php');


// DBからユーザーデータを取得
$db_user = getDbUser($_SESSION['user_id']);
$default_img = 'default.jpeg';
$messages = getUsersMessage();
// echo "<pre>"; var_dump($messages); echo"</pre>";
// POSTからのメッセージ投稿保存処理
if(!empty($_POST)) {
    $message = $_POST['message'];
    // 返信するフラグあればメッセージidを入れる
    $reply_id = '1';

    // バリデーションチェック
    validationRequired($message, 'message');
    validationMax($message, 'message', 100);
    // メッセージ投稿処理
    if(empty($err_msg)) {
        try {
            $dbh = dbConnect();
            $sql = 'INSERT INTO message (message, user_id, reply_id, modify_time, create_date) VALUE (:message, :u_id, :r_id, :m_time, :c_date)';
            $data = array(':message' => $message, ':u_id' => $_SESSION['user_id'], ':r_id' => $reply_id, ':m_time' => date('Y-m-d H:i:s'), ':c_date' => date('Y-m-d H:i:s'));
            $stmt = $dbh->prepare($sql);
            $result = $stmt->execute($data);
            header('Location: bord.php');
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
            <textarea class="form-control" name="message" rows="8" cols="40"></textarea>
            <p class="err_msg">
                <?php if(!empty($err_msg['message'])) echo $err_msg['message'];  ?>
            </p>
        </div>
        <div class="text-center">
            <input type="submit" class="btn-block w-20 btn-primary my-5 p-2  mr-auto" value="投稿する">
        </div>
    </form>

    <!-- メッセージ表示領域 -->
    <section style="width:70%; min-height: 1000px; background-color: lightgreen;" class="mx-auto my-5 p-5">
        <?php foreach($messages as $message): ?>
            <div style="overflow:hidden; width: 150px; height: 150px; background-color: white;">
                <?php if(!empty($message['thumbnail'])): ?>
                    <img class="block" style="width: 100%; height:auto;" src="<?php echo 'img/' . $message['thumbnail']; ?>" alt="あなたのプロフィール画像">
                <?php else: ?>
                    <img style="width: 100px;height:100px;" src="<?php echo 'img/' . $default_img; ?>" alt="デフォルト画像">
                <?php endif; ?>
            </div>
            <span><?php echo $message['user_name']; ?></span>
            <span class="pr-3"><?php echo $message['create_date']; ?></span>
            <!-- TODO 自分のアカウントメッセージだけに表示 -->
            <?php //if($_SESSION['user_id'] == $messages['user_id']): ?>
            <a href="delete.php?message_id=<?php echo $message['id']; ?>">削除</a>
            <a href="editMessage.php?message_id=<?php echo $message['id']; ?>" class="pr-3">編集</a>
            <?php //endif; ?>
            <p class="mb-5 mt-3 p-3" style="border-radius: 8px; background-color: #f1f1f1; color: #555;"><?php echo $message['message']; ?></p>
            <?php // echo "<pre>"; var_dump($message); echo"</pre>"; ?>
        <?php endforeach; ?>
    </section>
</main>

<?php require('footer.php'); ?>