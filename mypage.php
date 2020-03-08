<?php
ini_set('display_errors', 1);

require('./functions.php');
require('./validation.php');
require('./dbConnect.php');
require('./loginCheck.php');

$db_user = getDbUser($_SESSION['user_id']);
$default_img = 'default.jpeg';

?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>

<main class="container mt-5">
    <h1 class="page-title text-center my-5 col-8 mx-auto pb-3">マイページ</h1>
    <section class="profile p-5 mb-5">
        <div class="media bg-light rounded row">
            <?php if(!empty($db_user['thumbnail'])): ?>
                <img class="m-5 col-3 bg-white rounded" src="<?php echo 'img/' . $db_user['thumbnail']; ?>" alt="あなたのプロフィール画像">
            <?php else: ?>
                <img style="width:150px; height:150px;margin-top: 20px;" src="<?php echo 'img/' . $default_img; ?>" alt="デフォルト画像">
            <?php endif; ?>
            <div class="col-9 media-body">
                <h2 class="mb-3 pt-5"><?php echo $db_user['user_name']; ?></h2>
                <p class="h5 pb-5"><?php echo $db_user['profile_text']; ?></p>
            </div>
        </div>
        <div class="row">
            <a class="btn btn-primary block m-3" href="profEdit.php">プロフィール編集</a>
            <a class="btn btn-primary block m-3" href="bord.php">みんなのひとりごと板</a>
            <a class="btn btn-primary block m-3" href="withdraw.php">退会する</a>
        </div>
    </section>
</main>

<?php require('footer.php'); ?>