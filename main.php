<?php
session_start();
try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
} else {
    header('location:login.php');
    exit();
}

if ($member['is_admin'] == 1) {
    header('location:management.php');
};
$myid = htmlspecialchars($member['id'], ENT_QUOTES);
$myname = htmlspecialchars($member['name'], ENT_QUOTES);
$myimage = htmlspecialchars($member['picture'], ENT_QUOTES);




$posts = $db->query('SELECT * FROM members as m 
LEFT OUTER JOIN  posts as p ON m.id = p.member_id
LEFT OUTER JOIN  (SELECT message_id, COUNT(id) AS cnt FROM likes GROUP BY message_id) as l ON p.id = l.message_id
ORDER BY p.created DESC');
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP02</title>
    <link rel="stylesheet" href="css/main.css">
    <script src="jquery.js"></script>
</head>

<body>
    <div class="ui">
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
                        <a href="profile.php?member_id=<?= $post['member_id'] ?>"><img src="<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)) ?>"></a>
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
                            <?php if ($_SESSION['id'] == $post['member_id']) : ?>
                                [<span><a href="delete.php?id=<?= $id ?>" class="delete">削除</a></span>]
                            <?php endif; ?>
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
        <div class="btn">
            <button id="up">投稿する</button>
            <div class="out"><a href="logout.php">log out</a></div>
        </div>
        <form action="upload.php" method="POST" class="text-box" id="text-box">
            <textarea name="message" id="" cols="30" rows="10"></textarea>
            <p><input type="submit"></p>
        </form>

    </div>
    <script>
        $('#text-box').hide();
        $('#up').on('click', function() {
            $('#up').hide();
            $('#text-box').show();
        })
    </script>
</body>

</html>