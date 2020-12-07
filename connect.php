<?php
session_start();
// データベースに接続

try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}

$name = htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES);
$email = htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES);
$password = sha1(htmlspecialchars($_SESSION['join']['password'], ENT_QUOTES));
$image = htmlspecialchars('img/' . $_SESSION['join']['image'], ENT_QUOTES);


$statement = $db->prepare('INSERT INTO members SET name=?,email=?,password=?,picture=?,created=NOW()');
$statement->execute(array($name, $email, $password, $image));
unset($_SESSION['join']);

header('location:clear.php')

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP02</title>
</head>

<body>

</body>

</html>