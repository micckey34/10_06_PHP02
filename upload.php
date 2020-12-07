<?php
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
    $message = $db->prepare('INSERT INTO posts SET id=?,message=?,member_id=?,created=NOW()');
    echo ($_POST['message']);
    echo ($member['id']);
    $message->execute(array(NULL, $_POST['message'], $member['id']));
} else {
    echo ('馬鹿野郎');
}
header('location:main.php');
