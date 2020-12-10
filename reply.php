<?php
var_dump($_POST);
session_start();
try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}

$members = $db->prepare('SELECT * FROM members WHERE id=?');
$members->execute(array($_SESSION['id']));
$member = $members->fetch();


if ($_POST['message'] !== '') {
    $message = $db->prepare('INSERT INTO posts SET id=?,message=?,member_id=?,reply_message_id=?,reply_source=?,created=NOW()');
    $message->execute(array(NULL, $_POST['message'], $member['id'], $_POST['reply_message_id'], $_POST['reply_source']));
} else {
    echo ('馬鹿野郎');
}
header('location:main.php');
