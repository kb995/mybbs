 <?php

// セッション
session_start();
$sessionLimit = 60 * 60;

// DBのユーザー情報取得
// login.phpで保存したSESSION['user_id']を引数に、userテーブルから全ての情報を取得
function getDbUser($user_id) {
    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM users WHERE id = :userid';
        $data = array(':userid' => $user_id);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
    } catch (Exception $e) {
        echo '例外エラー発生 : ' . $e->getMessage();
        $err_msg['etc'] = 'しばらくしてから再度試してください';
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// みんなのメッセージ情報取得
function getUsersMessage() {
    try {
        $dbh = dbConnect();
        $sql = 'SELECT users.user_name, users.thumbnail, message.id, message.user_id, message.message, message.create_date FROM message JOIN users ON message.user_id = users.id ORDER BY message.create_date DESC';
        $stmt = $dbh->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo '例外エラー発生 : ' . $e->getMessage();
        $err_msg['etc'] = 'しばらくしてから再度試してください';
    }
    return $result;
}

// ひとりのメッセージ取得
function getOneMessage($message_id) {
    try {
        $dbh = dbConnect();
        $sql = 'SELECT users.user_name, users.thumbnail, message.id, message.message, message.create_date FROM message JOIN users ON message.user_id = users.id WHERE message.id = :message_id ';
        $data = array(':message_id' => $message_id);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        $message = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo '例外エラー発生 : ' . $e->getMessage();
        $err_msg['etc'] = 'しばらくしてから再度試してください';
    }
    return $message;
}



// サニタイズ
function h($str){
    return htmlspecialchars($str,ENT_QUOTES);
  }