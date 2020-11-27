<?php
include("includes/config.php");
include("includes/classes/Account.php");
include("includes/classes/Constants.php");

//A new blank account is created
$account = new Account($connection);

include("includes/handlers/register-handler.php");
include("includes/handlers/login-handler.php");

function getInputValue($name)
{
    if (isset($_POST[$name])) {
        echo $_POST[$name];
    }
}

?>

<html lang="en">
<head>
    <title>Welcome to Musis!</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;500;700&display=swap"
          rel="stylesheet">
    <link rel="icon" type="image/svg" href="assets/images/icons/musical_notes.svg">
    <link rel="stylesheet" type="text/css" href="assets/css/register.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
</head>
<body>

<?php
//Handle showing/hiding register/login page
if (isset($_POST['registerButton'])) {
    echo '<script>
            $(document).ready(() => {
            $("#loginForm").hide();
            $("#registerForm").show();
            });
        </script>';
} else {
    echo '<script>
            $(document).ready(() => {
            $("#loginForm").show();
            $("#registerForm").hide();
            });
        </script>';
}

?>

<div id="background">

    <div id="loginContainer">

        <div id="inputContainer">

            <form id="loginForm" action="register.php" method="POST">
                <div class="formHeadline">Đăng nhập</div>
                <div class="formDescription">để hoà mình vào những giai điệu tuyệt vời</div>

                <p>
                    <?php echo $account->getError(Constants::$loginFailed); ?>
                    <label for="loginUsername"></label>
                    <input id="loginUsername" name="loginUsername" type="text" placeholder="Tên đăng nhập"
                           value="<?php getInputValue('loginUsername') ?>" required>
                </p>

                <p>
                    <label for="loginPassword"></label>
                    <input id="loginPassword" name="loginPassword" type="password" placeholder="Mật khẩu" required>
                </p>

                <button type="submit" name="loginButton">Đăng nhập</button>

                <div class="hasAccountText">
                    <span id="hideLogin">Bạn chưa có tài khoản? Nhấp vào đây để đăng ký</span>
                </div>

            </form>


            <form id="registerForm" action="register.php" method="POST">
                <div class="formHeadline">Đăng ký</div>
                <div class="formDescription">để hoà mình vào những giai điệu tuyệt vời</div>

                <div class="flexbox">
                    <p>
                        <?php echo $account->getError(Constants::$usernameCharacters); ?>
                        <?php echo $account->getError(Constants::$usernameTaken); ?>
                        <label for="username"></label>
                        <input id="username" name="username" type="text" placeholder="Tên đăng nhập"
                               value="<?php getInputValue('username') ?>"
                               required>
                    </p>

                    <p>
                        <?php echo $account->getError(Constants::$emailInvalid); ?>
                        <?php echo $account->getError(Constants::$emailTaken); ?>
                        <label for="email"></label>
                        <input id="email" name="email" type="email" placeholder="Email"
                               value="<?php getInputValue('email') ?>" required>
                    </p>
                </div>

                <div class="flexbox">
                    <p>
                        <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                        <label for="firstName"></label>
                        <input id="firstName" name="firstName" type="text" placeholder="Tên"
                               value="<?php getInputValue('firstName') ?>"
                               required>
                    </p>

                    <p>
                        <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                        <label for="lastName"></label>
                        <input id="lastName" name="lastName" type="text" placeholder="Họ"
                               value="<?php getInputValue('lastName') ?>"
                               required>
                    </p>
                </div>

                <div class="flexbox">
                    <p>
                        <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
                        <?php echo $account->getError(Constants::$passwordsNotAlphanumeric); ?>
                        <?php echo $account->getError(Constants::$passwordCharacters); ?>
                        <label for="password"></label>
                        <input id="password" name="password" type="password" placeholder="Mật khẩu" required>
                    </p>

                    <p>
                        <label for="password2"></label>
                        <input id="password2" name="password2" type="password" placeholder="Nhập lại mật khẩu" required>
                    </p>
                </div>

                <button type="submit" name="registerButton">Tạo tài khoản</button>

                <div class="hasAccountText">
                    <span id="hideRegister">Bạn đã có tài khoản? Nhấp vào đây để đăng nhập</span>
                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>