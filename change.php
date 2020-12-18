<?php
error_reporting(E_ALL & ~E_NOTICE);
$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$old_password = sha1($_POST['old_password']);
$new_password = sha1($_POST['new_password']);
$image = date('YmdHis') . $_FILES['image']['name'];
move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $image);

try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}
$members = $db->prepare('SELECT * FROM members WHERE id=?');
$members->execute(array($id));
$member = $members->fetch();
$pass = $member['password'];

if ($name !== '') {
    $changename = $db->prepare('UPDATE members SET name=? WHERE id=?');
    $changename->execute(array($name, $id));
    $n_result = '名前を変更しました';
};
if ($email !== '') {
    $changeemail = $db->prepare('UPDATE members SET email=? WHERE id=?');
    $changeemail->execute(array($email, $id));
    $e_result = 'メールアドレスを変更しました。';
};
if ($old_password !== '' && $newpassword !== '') {
    if ($old_password == $pass) {
        $changepass = $db->prepare('UPDATE members SET password=? WHERE id=?');
        $changepass->execute(array($new_password, $id));
        $result = 'パスワードを変更しました。';
    } else {
        $result = 'パスワードの変更に失敗しました。';
    }
} else {
    $result = '';
};

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP02</title>
    <link rel="stylesheet" href="css/change.css">
</head>

<body>
    <div class="ui">
        <?php if ($name !== '') : ?>
            <p><?= $n_result ?></p>
        <?php endif; ?>
        <?php if ($email !== '') : ?>
            <p><?= $e_result ?></p>
        <?php endif; ?>
        <?php if ($new_password !== '' && $old_password !== '') : ?>
            <p><?= $result ?></p>
        <?php endif; ?>
        <p><a href="acount.php?id=<?= $id ?>">変更画面に戻る</a></p>
        <p><a href="profile.php?member_id=<?= $id ?>">プロフィール画面に戻る</a></p>
    </div>
</body>

</html>