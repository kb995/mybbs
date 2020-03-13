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
    
    // 画像アップロード
    if($_FILES['upload']) {
        $upload_img = date('YmdHis') . $_FILES['upload']['name'];
        // 画像のバリデーション
        validationImgType($_FILES['upload']['name'], 'image');
        if(empty($err_msg)) {
            move_uploaded_file($_FILES['upload']['tmp_name'], 'upload_img/' . $upload_img);
         }
     }

    // バリデーションチェック
    validationRequired($message, 'message');
    validationMax($message, 'message', 100);
    // メッセージ投稿処理
    if(empty($err_msg)) {
        try {
            $dbh = dbConnect();
            $sql = 'INSERT INTO message (message, upload_img, user_id, modify_time, create_date) VALUE (:message, :upload_img, :u_id, :m_time, :c_date)';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':message', $message, PDO::PARAM_STR);
            $stmt->bindValue(':upload_img', $upload_img, PDO::PARAM_STR);
            $stmt->bindValue(':u_id', $_SESSION['user_id'], PDO::PARAM_STR);
            $stmt->bindValue(':m_time', date('Y-m-d H:i:s'));
            $stmt->bindValue(':c_date', date('Y-m-d H:i:s'));
            $stmt->execute();
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

<main class="container">
    <!-- メッセージフォーム -->
    <h1 class="page-title">みんなのひとりごと</h1>
    <form class="form" action="" method="post" enctype="multipart/form-data">
    <!-- --------------- bordのスタイルシート ------------------ -->
        <p class="bg-light rounded">
            <p><?php echo $db_user['user_name']; ?></p>
            <?php if(!empty($db_user['thumbnail'])): ?>
                <img src="<?php echo 'img/' . $db_user['thumbnail']; ?>" alt="あなたのプロフィール画像">
            <?php else: ?>
                <img src="<?php echo 'img/' . $default_img; ?>" alt="デフォルト画像">
            <?php endif; ?>
        </p>
        <div class="form-group mb-5">
            <label class="control-label" for="">ひとりごとをつぶやく</label>
            <textarea class="form-control" name="message" rows="8" cols="40"><?php if(!empty($_POST['message'])) echo $_POST['message']; ?></textarea>
            <p class="err_msg">
                <?php if(!empty($err_msg['message'])) echo $err_msg['message'];  ?>
            </p>
        </div>
        <!-- 画像アップロード -->
        <div class="form-group">
            <label class="control-label" for="">画像の添付</label>
            <input class="form-control" type="file" name="upload">
            <p class="err_msg">
                <?php if(!empty($err_msg['image'])) echo $err_msg['image'];  ?>
            </p>
        </div>
        <div class="text-center">
            <input type="submit" class="button" value="投稿する">
        </div>
    </form>

    <!-- 検索フォーム -->
    <form class="bg-light col-7 mx-auto my-5 p-5 center" action="search.php" method="get">
    <div class="form-group mb-5 row">
        <input class="form-control col-9" type="text" name="search">
        <input class="btn-info col-2" type="submit" value="検索">
    </div>
    </form>

    <!-- メッセージ表示領域 -->
    <section style="width:70%; min-height: 1000px;" class="mx-auto my-5 p-5 bg-light">
        <?php foreach($messages as $data): ?>
            <div style="overflow:hidden; width: 100px; height: 100px; background-color: white;">
                <?php if(!empty($data['thumbnail'])): ?>
                    <a href="profDetail.php?user_id=<?php echo $data['user_id']; ?>">
                        <img class="block" style="width: 100%; height:auto;" src="<?php echo 'img/' . $data['thumbnail']; ?>" alt="あなたのプロフィール画像">
                    </a>
                <?php else: ?>
                    <img style="width:100px;height:100px;" src="<?php echo 'img/' . $default_img; ?>" alt="デフォルト画像">
                <?php endif; ?>
            </div>
            <p class="p-2"><?php echo $data['user_name']; ?></p>
            <span class="pr-3"><?php echo $data['create_date']; ?></span>
            <?php if($_SESSION['user_id'] == $data['user_id']): ?>
                <a href="delete.php?message_id=<?php echo $data['id']; ?>">削除</a>
                <a href="editMessage.php?message_id=<?php echo $data['id']; ?>" class="pr-3">編集</a>
            <?php endif; ?>

            <div class="mb-5" style="border-radius: 8px; background-color: #f1f1f1; color: #555;">
                 <p class="p-3 my-3"><?php echo $data['message']; ?></p>
                <?php if(!empty($data['upload_img'])): ?>
                <p class="mx-auto" style="width: 400px;height:400px;"><img style="max-width:100%;" src="upload_img/<?php echo $data['upload_img']; ?>" alt="アップロードイメージ"></p>
                <?php endif; ?>
            </div>
            <hr class="my-5">
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