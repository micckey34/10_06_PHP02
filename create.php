<?php
session_start();
$image = date('YmdHis') . $_FILES['image']['name'];
echo ($image);
move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $image);
$_SESSION['join'] = $_POST;
$_SESSION['join']['image'] = $image;
header('location:check.php');
