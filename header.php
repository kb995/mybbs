<header>
    <nav class="navbar bg-dark">
        <div class="container">
            <h1 class="text-light sitelogo">MyBBS</h1>
            <?php if(empty($_SESSION['login_flg'])) : ?>
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link" href="signup.php">新規登録</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">ログイン</a></li>
                </ul>
            <?php else : ?>
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link" href="mypage.php">マイページ</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">ログアウト</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </nav>
</header>