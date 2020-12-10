<?php
session_start();
echo ($_GET['id']);
try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}
if (isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];
    $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    $del = $db->prepare('UPDATE posts SET good=? WHERE id=?');
    $del->execute(array($message['good'] + 1, $id));
};
header('location:main.php');
exit();
