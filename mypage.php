<?php

require('./functions.php');
require('./loginAuth.php');

$db_user = getDbUser($_SESSION['user_id']);
$default_img = 'default.jpeg';

?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>

<main class="container mypage">
    <h1 class="page-title">マイページ</h1>
        <div class="bg-light prof">
            <p class="prof-img">
                <?php if(!empty($db_user['thumbnail'])): ?>
                    <img src="<?php echo 'img/' . $db_user['thumbnail']; ?>" alt="あなたのプロフィール画像">
                <?php else: ?>
                    <img src="<?php echo 'img/' . $default_img; ?>" alt="デフォルト画像">
                <?php endif; ?>
            </p>
            <h2 class="prof-name"><?php echo $db_user['user_name']; ?></h2>
            <p class="prof-text"><?php echo $db_user['profile_text']; ?></p>
            <div class="prof-btn">
                <a class="button" href="bord.php">みんなのひとりごと板</a>
                <a class="button" href="profEdit.php">プロフィール編集</a>
            </div>
            <p class="withdraw" >
                <a href="withdraw.php">退会する&raquo;</a>
            </p>
        </div>
</main>

<?php require('footer.php'); ?>