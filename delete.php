<?php
session_start();
try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}
if (isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();

    $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    if ($member['is_admin'] == 1) {
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($id));
    } else {
        if ($message['member_id'] == $_SESSION['id']) {
            $del = $db->prepare('DELETE FROM posts WHERE id=?');
            $del->execute(array($id));
        }
    }
};
header('location:main.php');
exit();
