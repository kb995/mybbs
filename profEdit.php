<?php

require('./functions.php');
require('./validation.php');
require('./loginAuth.php');


// DBからユーザーデータを取得
$db_user = getDbUser($_SESSION['user_id']);
$default_img = 'default.jpeg';

if(!empty($_POST)) {
    // バリデーション
    validationRequired($_POST['name'], 'name');
    validationMax($_POST['name'], 'name', 20);
    emailCheck($_POST['email'], 'email');
    validationRequired($_POST['profile'], 'profile');
    validationMax($_POST['profile'], 'profile');

    if(empty($err_msg)) {
        // 入力内容をセッションに入れて保存
        $_SESSION['profedit'] = $_POST;
        $_SESSION['profedit']['prof_img'] = $db_user['thumbnail'];
    
        // FILESにアップロードある時、画像の名前作成 & 一時保存ファイルから画像専用ファイルに移動
        // セッションに入れて保存
        if(!empty($_FILES['thumbnail']['name'])) {
           $img = date('YmdHis') . $_FILES['thumbnail']['name'];
           // 画像のバリデーション
           validationImgType($img, 'image');
           if(empty($err_msg)) {
               $_SESSION['profedit']['prof_img'] = $img;
               move_uploaded_file($_FILES['thumbnail']['tmp_name'], 'img/' . $img);
            }
        }
        header('Location: profCheck.php');
    }
}
?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>

<main class="container">
    <h1 class="page-title">プロフィール編集</h1>
    <form class="bg-light form" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label class="control-label" for="">ニックネーム</label>
            <input class="form-control" type="text" name="name" value="<?php echo $db_user['user_name']; ?>">
            <p class="err_msg">
                <?php if(!empty($err_msg['name'])) echo $err_msg['name'];  ?>
            </p>
        </div>
        <div class="form-group">
            <label class="control-label" for="">メールアドレス</label>
            <input class="form-control" type="email" name="email" value="<?php echo $db_user['email']; ?>">
            <p class="err_msg">
                <?php if(!empty($err_msg['email'])) echo $err_msg['email'];  ?>
            </p>
        </div>
        <div class="form-group">
            <label class="control-label" for="">プロフィール文</label>
            <textarea class="form-control" name="profile" rows="4" cols="40"><?php echo $db_user['profile_text']; ?></textarea>
            <p class="err_msg">
                <?php if(!empty($err_msg['profile'])) echo $err_msg['profile'];  ?>
            </p>
        </div>
        <div class="form-group">
            <label class="control-label" for="">プロフィール画像</label>
            <p class="col-5">
                <?php if(!empty($db_user['thumbnail'])): ?>
                    <img class="bg-light" src="<?php echo 'img/' . $db_user['thumbnail']; ?>" alt="プロフィール画像">
                <?php else: ?>
                    <img src="<?php echo 'img/' . $default_img; ?>" alt="デフォルト画像">
                <?php endif; ?>
            </p>
            <input class="form-control input-file" type="file" name="thumbnail">
            <p class="err_msg">
                <?php if(!empty($err_msg['image'])) echo $err_msg['image'];  ?>
            </p>
        </div>
        <div class="text-right">
            <input class="button" type="submit" value="確認する">
        </div>
    </form>
</main>

<?php require('footer.php'); ?>