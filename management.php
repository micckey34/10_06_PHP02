<?php
session_start();
function connect_to_db()
{
    // DB接続の設定
    // DB名は`gsacf_x00_00`にする
    $dbn = 'mysql:dbname=php02;charset=utf8;port=3306;host=localhost';
    $user = 'root';
    $pwd = '';

    try {
        // ここでDB接続処理を実行する
        return new PDO($dbn, $user, $pwd);
    } catch (PDOException $e) {
        // DB接続に失敗した場合はここでエラーを出力し，以降の処理を中止する
        echo json_encode(["db error" => "{$e->getMessage()}"]);
        exit();
    }
}
$pdo = connect_to_db();

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
} else {
    header('location:login.php');
    exit();
}
$myid = $_SESSION['id'];

try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}

$sql = 'SELECT * FROM members';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $posts = $db->query('SELECT * FROM members as m 
            LEFT OUTER JOIN  posts as p ON m.id = p.member_id
            LEFT OUTER JOIN  (SELECT message_id, COUNT(id) AS cnt FROM likes GROUP BY message_id) as l ON p.id = l.message_id
            ORDER BY p.created DESC');

    unset($value);
}



?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP02</title>
    <link rel="stylesheet" href="css/manage.css">
    <script src="jquery.js"></script>
</head>

<body>
    <h1>管理者ページ</h1>
    <div class="ui">
        <div class="users">
            <div class="user-list">
                <?php foreach ($users as $user) : ?>
                    <?php
                    $id = htmlspecialchars($user['id']);
                    $name = htmlspecialchars($user['name'], ENT_QUOTES);
                    $email = htmlspecialchars($user['email'], ENT_QUOTES);
                    $created = htmlspecialchars($user['created'], ENT_QUOTES);
                    ?>
                    <div class="user">
                        <details>
                            <summary><?= $name ?></summary>
                            <img src="<?= $user['picture'] ?>" alt="">
                            <table>
                                <tr>
                                    <th>ID</th>
                                    <td><?= $id ?></td>
                                </tr>
                                <tr>
                                    <th>E-Mail</th>
                                    <td><?= $email ?></td>
                                </tr>
                                <tr>
                                    <th>登録日時</th>
                                    <td><?= $created ?></td>
                                </tr>
                            </table>
                            <div class="acount">
                                <p><a href="acount.php?id=<?= $id ?>">アカウントの変更</a></p>
                                <p><a href="deletecheck.php?id=<?= $id ?>">アカウントの削除</a></p>
                            </div>
                        </details>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="out">
                <a href="logout.php">log out</a>
            </div>
        </div>

        <div class="time-line">

            <div class="output">
                <?php foreach ($posts as $post) : ?>
                    <?php
                    $id = htmlspecialchars($post['id']);
                    $name = htmlspecialchars($post['name'], ENT_QUOTES);
                    $message = htmlspecialchars($post['message'], ENT_QUOTES);
                    $created = htmlspecialchars($post['created'], ENT_QUOTES);
                    $good = htmlspecialchars($post['cnt'], ENT_QUOTES);
                    $reply_source = htmlspecialchars($post['reply_source'], ENT_QUOTES);
                    ?>
                    <div class="uplord-data">
                        <div class="prof">
                            <a href="profile.php?member_id=<?= $post['member_id'] ?>"><img src="<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)) ?>" width=64px></a>
                            <p>(<?= $name ?>)</p>
                        </div>
                        <div class="text">
                            <?php if ($post['reply_message_id'] !== NULL) : ?>
                                <p class="reply"><?= $reply_source ?></p>
                            <?php endif; ?>
                            <p class="message"><?= $message ?></p>
                            <p class="t-d">
                                <span class="time"><?= $created ?></span>
                                <button id="re-btn<?= $post['id'] ?>" class="re-btn">Re</button>
                                [<span><a href="delete.php?id=<?= $id ?>" class="delete">削除</a></span>]
                                <span class="good"><?= $good ?><a href="good.php?id=<?= $id ?>&member_id=<?= $myid ?>">👍</a></span>
                            </p>
                        </div>
                    </div>
                    <div id="reply-box<?= $id ?>" class="reply-box">
                        <form action="reply.php" method="POST" id="rep-form<?= $id ?>">
                            <textarea name="message" id="" cols="30" rows="10"></textarea>
                            <input type="hidden" name="reply_message_id" value="<?= $id ?>">
                            <input type="hidden" name="reply_source" value="(<?= $name ?>)<?= $message ?>⇨">
                            <p>
                                <button type="button" id="can-btn<?= $id ?>">キャンセル</button>
                                <button type="submit" form="rep-form<?= $id ?>">送信</button>
                            </p>
                        </form>
                    </div>
                    <script>
                        $('#reply-box<?= $id ?>').hide();
                        $('#re-btn<?= $id ?>').on('click', function() {
                            $('#reply-box<?= $id ?>').show();
                        });
                        $('#can-btn<?= $id ?>').on('click', function() {
                            $('#reply-box<?= $id ?>').hide();
                        });
                    </script>
                <?php endforeach; ?>
            </div>
            <div class="text-box">
                <form action="upload.php" method="POST" class="text-box" id="text-box">
                    <textarea name="message" id="" cols="30" rows="10"></textarea>
                    <button type="submit">投稿</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>