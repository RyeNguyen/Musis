<?php

include("../../config.php");

if(!isset($_POST['username'])) {
    echo "Lỗi: không xác định được người dùng";
    exit();
}

if(isset($_POST['email']) && $_POST['email'] != "") {

    $username = $_POST['username'];
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email không hợp lệ!";
        exit();
    }

    $emailCheck = mysqli_query($connection, "SELECT email FROM users WHERE email='$email' AND username != '$username'");
    if (mysqli_num_rows($emailCheck) > 0) {
        echo "Email đã được sử dụng bởi người dùng khác!";
        exit();
    }

    $updateQuery = mysqli_query($connection, "UPDATE users SET email = '$email' WHERE username='$username'");
    echo "Cập nhật thành công.";

} else {
    echo "Bạn phải điền email!";
}