<?php
session_start();
// if ($_POST['name'] === '') {
//     $error['name'] = 'hoge';
// };
// if ($_POST['email'] === '') {
//     $error['email'] = 'hoge';
// };
// if ($_POST['password'] === '') {
//     $error['password'] = 'hoge';
// };
// var_dump($_FILES['image']['name']);
$image = date('YmdHis') . $_FILES['image']['name'];
echo ($image);
move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $image);
$_SESSION['join'] = $_POST;
$_SESSION['join']['image'] = $image;
header('location:check.php');
