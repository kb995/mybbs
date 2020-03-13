<?php

require('./functions.php');
require('./validation.php');
require('./loginAuth.php');

$db_user = getDbUser($_SESSION['user_id']);

if(!empty($_SESSION['profedit'])) {
    $name = $_SESSION['profedit']['name'];
    $email = $_SESSION['profedit']['email'];
    $profile = $_SESSION['profedit']['profile'];
    $img = $_SESSION['profedit']['prof_img'];
    $default_img = 'default.jpeg';

    if(!empty($_POST)) {
        try {
            $dbh = dbConnect();
            $sql = 'UPDATE users SET user_name = :user_name, email = :email, profile_text = :profile_text, thumbnail = :thumbnail, modify_time = :modify_time  WHERE id = :id';
            $data = array(':user_name' => $name, ':email' => $email, ':profile_text' => $profile, ':thumbnail' => $img, ':modify_time' => date('Y-m-d H:i:s'), ':id' => $db_user['id']);
            $stmt = $dbh->prepare($sql);
            $result = $stmt->execute($data);
            if($result) {
                header('Location: mypage.php');
            }
        } catch (PDOException $e) {
            echo '例外エラー発生 : ' . $e->getMessage();
            $err_msg['etc'] = 'しばらくしてから再度試してください';
        }
    }
}
?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>

<main class="container prof">
    <h1 class="page-title">入力確認画面</h1>
    <form class="bg-light form" action="" method="post" enctype="multipart/form-data">
    <p class="text-center check-text-top">【 記入した内容を確認して、「更新する」をクリックして下さい 】</p>
            <p class="check-text">プロフィール画像</p>
            <p class="check-img">
                <?php if(!empty($img)): ?>
                    <img src="<?php echo 'img/' . $img; ?>" alt="プロフィール画像">
                <?php elseif(!empty($db_user['thumbnail'])): ?>
                    <img src="<?php echo 'img/' . $db_user['thumbnail']; ?>" alt="プロフィール画像">
                <?php else: ?>
                    <img src="<?php echo 'img/' . $default_img; ?>" alt="デフォルト画像">
                <?php endif; ?>
            </p>
            <p class="check-text">ニックネーム : <?php echo h($_SESSION['profedit']['name']); ?></p>
            <p class="check-text">メールアドレス : <?php echo h($_SESSION['profedit']['email']); ?></p>
            <p class="check-text">パスワード : 【表示されません】</p>
            <p class="check-text">プロフィール文 : 
                <?php echo h($_SESSION['profedit']['profile']); ?>
            </p>
            <a class="text-left back-link" href="profEdit.php">&lt;戻る</a>
            <div class="text-right">
                <input type="submit" name="submit" class="button" value="更新する">
                <input type="hidden" name="submit">
            </div>
        </form>
</main>

<?php require('footer.php'); ?>