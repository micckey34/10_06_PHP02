<?php
$id = $_GET['id'];
echo $id;
try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}
$members = $db->prepare('DELETE FROM members WHERE id=?');
$members->execute(array($id));

$posts = $db->prepare('DELETE FROM posts WHERE member_id=?');
$posts->execute(array($id));

header('location:main.php');
