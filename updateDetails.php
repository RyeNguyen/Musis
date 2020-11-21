<?php
include("includes/includedFiles.php");
?>

<div class="userDetails">

    <div class="container">
        <h2>EMAIL</h2>
        <input type="text" class="email" name="email" placeholder="Email của bạn..."
               value="<?php echo $userLoggedIn->getEmail(); ?>">
        <span class="message"></span>
        <button class="button" onclick="updateEmail('email');">Cập nhật email</button>
    </div>

    <div class="gradientLine"></div>

    <div class="container">
        <h2>Mật khẩu</h2>
        <input type="password" class="oldPassword" name="oldPassword" placeholder="Mật khẩu hiện tại">
        <input type="password" class="newPassword1" name="newPassword1" placeholder="Mật khẩu mới">
        <input type="password" class="newPassword2" name="newPassword2" placeholder="Xác nhận mật khẩu mới">
        <span class="message"></span>
        <button class="button" onclick="updatePassword('oldPassword', 'newPassword1', 'newPassword2');">Cập nhật mật khẩu</button>
    </div>

</div>
