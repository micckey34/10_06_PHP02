<?php
session_start();
// データベースに接続

try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}

$login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
$login->execute(array(
    $_POST['email'],
    sha1($_POST['password'])
));
$member = $login->fetch();
if ($member) {
    $_SESSION['id'] = $member['id'];
    $_SESSION['time'] = time();
    header('location:main.php');
    exit();
} else {
    echo ('aaa');
    header('location:login.php');
};
