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
$messages = getSearchMessage($start, 5);

?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>

<main class="container mt-5">
    <h1 class="page-title text-center my-5 col-8 mx-auto pb-3">「<?php echo $_GET['search']; ?>」の検索結果</h1>
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