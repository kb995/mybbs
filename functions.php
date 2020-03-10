 <?php
ini_set('display_errors', 1);

// セッション
session_start();
$sessionLimit = 60 * 60;

// DB接続
function dbConnect() {
    $dsn = 'mysql:dbname=mybbs;host=localhost;charset=utf8';
    $user = 'root';
    $pass = 'root';
    $dbh = new PDO($dsn, $user, $pass);
    return $dbh;
}

// DBのユーザー情報取得
function getDbUser($user_id) {
    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM users WHERE id = :user_id AND delete_flg = 0';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        echo '例外エラー発生 : ' . $e->getMessage();
        $err_msg['etc'] = 'しばらくしてから再度試してください';
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// みんなのメッセージ情報取得
// 引数①取得開始位置, 引数②取得個数
function getUsersMessage($start, $count) {
    try {
        $dbh = dbConnect();
        $sql = 'SELECT users.user_name, users.thumbnail, message.id, message.user_id, message.message, message.create_date FROM message JOIN users ON message.user_id = users.id WHERE users.delete_flg = 0 AND message.delete_flg = 0 ORDER BY message.create_date DESC LIMIT :start, :count';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':count', $count, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '例外エラー発生 : ' . $e->getMessage();
        $err_msg['etc'] = 'しばらくしてから再度試してください';
    }
    return $result;
}

// ひとりのメッセージ取得
function getOneMessage($message_id) {
    try {
        $dbh = dbConnect();
        $sql = 'SELECT users.user_name, users.thumbnail, message.id, message.message, message.create_date FROM message JOIN users ON message.user_id = users.id WHERE message.id = :message_id';
        $data = array(':message_id' => $message_id);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        $message = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '例外エラー発生 : ' . $e->getMessage();
        $err_msg['etc'] = 'しばらくしてから再度試してください';
    }
    return $message;
}



// サニタイズ
function h($str){
    return htmlspecialchars($str,ENT_QUOTES);
  }