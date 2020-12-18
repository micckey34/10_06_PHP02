<?php
try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}
$members = $db->prepare('SELECT * FROM members WHERE id=?');
$members->execute(array($_GET['id']));
$member = $members->fetch();
$name = $member['name'];
$email = $member['email'];
$password = $member['password'];

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
        <h2>登録情報編集</h2>
        <form action="change.php" method="POST" enctype="multipart/form-data">
            <p>名前:<?= $name ?><br><input type="text" name="name" id=""></p>
            <p>メールアドレス:<?= $email ?><br><input type="text" name="email" id=""></p>
            <p>パスワードを変更する場合は<br>どちらもご記入ください。</p>
            <p>現在のパスワード<br><input type="password" name="old_password"></p>
            <p>新しいパスワード<br><input type="password" name="new_password"></p>
            <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
            <p class="btn"><button type='submit'>変更</button></p>
        </form>
    </div>
</body>

</html>