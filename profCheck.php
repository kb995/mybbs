<?php
ini_set('display_errors', 1);

require('./functions.php');
require('./validation.php');
require('./dbConnect.php');
require('./loginCheck.php');

$db_user = getDbUser($_SESSION['user_id']);

if(!isset($_SESSION['profedit'])) {
    header('Location: login.php');
    exit;
}

if(!empty($_SESSION['profedit'])) {
    $name = $_SESSION['profedit']['name'];
    $email = $_SESSION['profedit']['email'];
    $profile = $_SESSION['profedit']['profile'];
    $img = $_SESSION['profedit']['prof_img'];
    $default_img = 'default.jpeg';

    if(!empty($_POST)) {
        // bindValueに変更
        // PDOException
        try {
            $dbh = dbConnect();
            $sql = 'UPDATE users SET user_name = :user_name, email = :email, profile_text = :profile_text, thumbnail = :thumbnail, modify_time = :modify_time  WHERE id = :id';
            $data = array(':user_name' => $name, ':email' => $email, ':profile_text' => $profile, ':thumbnail' => $img, ':modify_time' => date('Y-m-d H:i:s'), ':id' => $db_user['id']);
            $stmt = $dbh->prepare($sql);
            $result = $stmt->execute($data);
            if($result) {
                // todo: JSでヘッダーにアラートつくる
                header('Location: mypage.php');
            }
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
    <h1 class="page-title text-center my-5 col-8 mx-auto pb-3">入力確認画面</h1>
    <section class="profile p-5 mb-5">
        <h4 class="text-center pb-5">記入した内容を確認して、「登録する」をクリックして下さい</h4>
        <form class="bg-light col-7 mx-auto p-5" action="" method="post" enctype="multipart/form-data">
            <h4>プロフィール画像</h4>
            <?php if(!empty($img)): ?>
                <img style="width:250px; height:150px;margin-top: 20px;" src="<?php echo 'img/' . $img; ?>" alt="プロフィール画像">
            <?php elseif(!empty($db_user['thumbnail'])): ?>
                <img class="width:250px; height:150px;margin-top: 20px;" src="<?php echo 'img/' . $db_user['thumbnail']; ?>" alt="プロフィール画像">
            <?php else: ?>
                <img style="width:150px; height:150px;margin-top: 20px;" src="<?php echo 'img/' . $default_img; ?>" alt="デフォルト画像">
            <?php endif; ?>
            <h4 class="p-3 pt-5">ニックネーム : <?php echo h($_SESSION['profedit']['name']); ?></h4>
            <h4 class="p-3">メールアドレス : <?php echo h($_SESSION['profedit']['email']); ?></h4>
            <h4 class="p-3">パスワード : 【表示されません】</h4>
            <h4 class="p-3">プロフィール文 : </h4>
            <h4 class="p-2">
                <?php echo h($_SESSION['profedit']['profile']); ?>
            </h4>
            <a class="btn btn-link" href="profEdit.php">&lt;戻る</a>
            <input type="submit" name="submit" class="btn-block w-20 btn-primary my-5 p-2" value="この内容で保存する">
            <input type="hidden" name="submit">
        </form>
    </section>
</main>

<?php require('footer.php'); ?>