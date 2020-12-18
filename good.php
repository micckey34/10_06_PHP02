<?php
session_start();

$message_id = $_GET['id'];
$member_id = $_GET['member_id'];


try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}
// $likes =  $db->prepare('SELECT COUNT(*) FROM likes WHERE message_id=? AND member_id=?');
// $likes->execute(array($message_id, $member_id));

$sql = 'SELECT COUNT(*) FROM likes WHERE message_id=:m_id AND member_id=:u_id';

$stmt = $db->prepare($sql);
$stmt->bindValue(':m_id', $message_id, PDO::PARAM_STR);
$stmt->bindValue(':u_id', $member_id, PDO::PARAM_STR);
$status = $stmt->execute();


if ($status == false) {
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    $like_count = $stmt->fetch();
    if ($like_count[0] != 0) {
        $sql = 'DELETE FROM likes WHERE message_id=:m_id AND member_id=:u_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':m_id', $message_id, PDO::PARAM_STR);
        $stmt->bindValue(':u_id', $member_id, PDO::PARAM_STR);
        $status = $stmt->execute();
    } else {
        $sql = 'INSERT INTO likes(id,message_id,member_id)VALUES(NULL,:m_id,:u_id)';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':m_id', $message_id, PDO::PARAM_STR);
        $stmt->bindValue(':u_id', $member_id, PDO::PARAM_STR);
        $status = $stmt->execute();
    };
};

// if (isset($_SESSION['id'])) {
//     $id = $_REQUEST['id'];
//     $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
//     $messages->execute(array($id));
//     $message = $messages->fetch();

//     $del = $db->prepare('UPDATE posts SET good=? WHERE id=?');
//     $del->execute(array($message['good'] + 1, $id));
// };
header('location:main.php');
exit();
