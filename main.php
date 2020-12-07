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
    // var_dump($member);
} else {
    header('location:login.php');
    exit();
}
$myid = htmlspecialchars($member['id'], ENT_QUOTES);
$myname = htmlspecialchars($member['name'], ENT_QUOTES);
$myimage = htmlspecialchars($member['picture'], ENT_QUOTES);

// $messages = $db->prepare('SELECT * FROM posts WHERE member_id=?');
// $messages->execute(array($_SESSION['id']));
// $message = $messages->fetch();
// $text = $message['message'];

$posts = $db->query('SELECT m.name,m.picture,p.* FROM members m , posts p 
WHERE m.id=p.member_id ORDER BY p.created DESC')

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP02</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <div class="ui">
        <div class="output">
            <?php foreach ($posts as $post) : ?>
                <div class="uplord-data">
                    <img src="<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)) ?>" alt="" class="img">
                    <div class="text">
                        <p><?php print(htmlspecialchars($post['message'], ENT_QUOTES)) ?><span class="name">(<?php print(htmlspecialchars($post['name'], ENT_QUOTES)) ?>)</span></p>
                        <p class="t-d">
                            <span class="time"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)) ?></span>
                            <?php if ($_SESSION['id'] == $post['member_id']) : ?>
                                [<span><a href="delete.php?id=<?php print(htmlspecialchars($post['id'])); ?>" class="delete">削除</a></span>]
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="btn">
            <button id="up">投稿する</button>
            <div class="out"><a href="login.php">log out</a></div>
        </div>
        <form action="upload.php" method="POST" class="text-box" id="text-box">
            <textarea name="message" id="" cols="30" rows="10"></textarea>
            <p><input type="submit"></p>
        </form>

    </div>
    <script src="jquery.js"></script>
    <script>
        $('#text-box').hide();
        $('#up').on('click', function() {
            $('#up').hide();
            $('#text-box').show();
        })
    </script>
</body>

</html>