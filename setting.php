<?php
    include("includes/includedFiles.php");
?>

<div class="entityInfo">

    <div class="centerSection">

        <div class="userInfo">
            <h1><?php echo $userLoggedIn->getFullName(); ?></h1>
        </div>

    </div>

    <div class="buttonItems">
        <button class="button" onclick="openPage('updateDetails.php')">Chi tiết người dùng</button>
        <button class="button" onclick="logout()">Đăng xuất</button>
    </div>

</div>
