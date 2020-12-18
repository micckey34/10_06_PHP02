<?php
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP02</title>
    <style>
        body {
            text-align: center;
        }

        .select {
            width: 500px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        a {
            display: block;
            height: 50px;
            line-height: 50px;
            width: 150px;
            text-decoration: none;
        }

        .no {
            background: gray;
            color: white;
        }

        .yes {
            background: red;
            color: white;
        }
    </style>
</head>

<body>
    <h1>アカウントの削除</h1>
    <h3>本当にアカウントを削除してもよろしいですか？</h3>
    <div class="select">
        <a href="main.php" class="no">いいえ</a><a href="acountdelete.php?id=<?= $id ?>" class="yes">はい</a>
    </div>
</body>

</html>