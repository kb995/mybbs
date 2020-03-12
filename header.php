<header class="header">
    <h1 class="logo">MyBBS</h1>
        <?php if(empty($_SESSION['login_flg'])) : ?>
            <ul class="menu">
                <li class="menu-item"><a class="menu-item-link" href="signup.php">新規登録</a></li>
                <li class="menu-item"><a class="menu-item-link" href="login.php">ログイン</a></li>
            </ul>
        <?php else: ?>
            <ul class="menu">
                <li class="menu-item"><a class="menu-item-link" href="mypage.php">マイページ</a></li>
                <li class="menu-item"><a class="menu-item-link" href="logout.php">ログアウト</a></li>
            </ul>
        <?php endif; ?>
</header>