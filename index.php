<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP02</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div id="warp" class="ui">
        <h1>会員登録</h1>
        <div id="content">
            <form action="create.php" method="POST" enctype="multipart/form-data">
                <p>Name</p>
                <p><input type="text" name="name" required></p>
                <p>E-Mail</p>
                <p><input type="text" name="email" required></p>
                <p>Password</p>
                <p><input type="password" name="password" required></p>
                <p>Image</p>
                <p><input type="file" name="image"></p>
                <p><input type="submit" value="確認" class="check"></p>
            </form>
            <a href="login.php" class="top-login">ログイン</a>
        </div>
    </div>
</body>

</html>