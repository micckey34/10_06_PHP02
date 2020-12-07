<?php
session_start();
if (!isset($_SESSION['join'])) {
    header('location:index.php');
    exit();
}
$name = htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES);
$email = htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES);
$password = htmlspecialchars($_SESSION['join']['password'], ENT_QUOTES);
$image = htmlspecialchars('img/' . $_SESSION['join']['image'], ENT_QUOTES);


?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP02</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="ui">
        <form action="connect.php">
            <p class="check">name</p>
            <p class="check"><span><?= $name ?></span></p>
            <p class="check">e-mail</p>
            <p class="check"><span><?= $email ?></span></p>
            <p class="check">pasword</p>
            <p class="check"><span>表示されません。</span></p>
            <img src="<?= $image ?>" alt="" height="200px">
            <p><input type="submit" value="登録" class="check"></p>
        </form>
    </div>
</body>

</html>