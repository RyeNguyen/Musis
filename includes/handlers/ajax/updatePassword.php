<?php

include("../../config.php");

if(!isset($_POST['username'])) {
    echo "Lỗi: không xác định được người dùng!";
    exit();
}

if (!isset($_POST['oldPassword']) || !isset($_POST['newPassword1']) || !isset($_POST['newPassword2'])) {
    echo "Lỗi: không xác định được mật khẩu!";
    exit();
}

if (isset($_POST['oldPassword']) == "" || isset($_POST['newPassword1']) == "" || isset($_POST['newPassword2']) == "") {
    echo "Làm ơn điền vào tất cả các trường!";
    exit();
}

$username = $_POST['username'];
$oldPassword = $_POST['oldPassword'];
$newPassword1 = $_POST['newPassword1'];
$newPassword2 = $_POST['newPassword2'];

$oldMd5 = md5($oldPassword);

$passwordCheck = mysqli_query($connection, "SELECT * FROM users WHERE username='$username' AND password='$oldMd5'");
if (mysqli_num_rows($passwordCheck) != 1) {
    echo "Mật khẩu không đúng!";
    exit();
}

if($newPassword1 != $newPassword2) {
    echo "Mật khẩu mới không khớp!";
    exit();
}

if (preg_match('/[^A-Za-z0-9]/', $newPassword1)) {
    echo "Mật khẩu của bạn chỉ được bao gồm chữ cái và số!";
}

if (strlen($newPassword1) > 30 || strlen($newPassword1) < 5) {
    echo "Mật khẩu của bạn phải có độ dài từ 5 đến 30 ký tự!";
    exit();
}

$newMd5 = md5($newPassword1);

$query = mysqli_query($connection, "UPDATE users SET password='$newMd5' WHERE username='$username'");
echo "Cập nhật thành công.";