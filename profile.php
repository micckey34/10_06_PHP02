<?php
session_start();
try {
    $db = new PDO('mysql:dbname=php02;host=localhost;charset=utf8', 'root', '');
} catch (PDOException $e) {
    print('接続エラー' . $e->getMessage());
}
$id = $_GET['member_id'];
$members = $db->prepare('SELECT * FROM members WHERE id=?');
$members->execute(array($id));
$member = $members->fetch();
$name = $member['name'];
$img = $member['picture'];

$posts = $db->prepare('SELECT * FROM posts WHERE member_id=? ORDER BY created DESC');
$posts->execute(array($id));


?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <title>PHP02</title>
    <script src="jquery.js"></script>
</head>

<body>
    <div class="ui">
        <div class="prof">
            <img src="<?= $img ?>" alt="">
            <p class="name"><?= $name ?></p>
        </div>
        <div class="output">
            <?php foreach ($posts as $post) : ?>
                <div class="uplord-data">
                    <?php
                    $id = htmlspecialchars($post['id'], ENT_QUOTES);
                    $member_id =
                        htmlspecialchars($post['member_id'], ENT_QUOTES);
                    $message = htmlspecialchars($post['message'], ENT_QUOTES);
                    $created = htmlspecialchars($post['created'], ENT_QUOTES);
                    $good = htmlspecialchars($post['good'], ENT_QUOTES);
                    $reply_source = htmlspecialchars($post['reply_source'], ENT_QUOTES);
                    ?>
                    <?php if ($post['reply_message_id'] !== NULL) : ?>
                        <p class="reply"><?= $reply_source ?></p>
                    <?php endif; ?>
                    <p class="message"><?= $message ?></p>
                    <p class="t-d">
                        <span class="time"><?= $created ?></span>
                        <button type="button" id="re-btn<?= $id ?>" class="re-btn">Re</button>
                        <?php if ($_SESSION['id'] == $member_id) : ?>
                            [<span><a href="delete.php?id=<?= $id ?>" class="delete">削除</a></span>]
                        <?php endif; ?>
                        <span class="good">(<?= $good ?>)<a href="good.php?id=<?= $id ?>">👍</a></span>
                    </p>
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
                    <script>
                        $('#reply-box<?= $id ?>').hide();
                        $('#re-btn<?= $id ?>').on('click', function() {
                            $('#reply-box<?= $id ?>').show();
                        });
                        $('#can-btn<?= $id ?>').on('click', function() {
                            $('#reply-box<?= $id ?>').hide();
                        });
                    </script>
                </div>
            <?php endforeach; ?>
        </div>
        <div>
            <a href="main.php" class="back">戻る</a>
        </div>
    </div>
</body>

</html>