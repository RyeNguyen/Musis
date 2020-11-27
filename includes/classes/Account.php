<?php

class Account
{

    private $connection;
    private $errorArray;

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->errorArray = array();
    }

    //Register user through validations
    public function register($username, $firstName, $lastName, $email, $password, $password2)
    {
        $this->validateUsername($username);
        $this->validateFirstName($firstName);
        $this->validateLastName($lastName);
        $this->validateEmail($email);
        $this->validatePassword($password, $password2);

        if (empty($this->errorArray)) {
            //Insert data to db
            return $this->insertUserDetails($username, $firstName, $lastName, $email, $password);
        } else {
            return false;
        }
    }

    //Show the error message to the UI
    public function getError($error)
    {
        if (!in_array($error, $this->errorArray)) {
            $error = "";
        }

        return "<div class='errorMessage'>$error</div>";
    }

    //Insert user details into table if there are no errors
    private function insertUserDetails($username, $firstName, $lastName, $email, $password)
    {
        $encryptedPassword = md5($password);
        $profilePic = "assets/images/profile-pictures/";
        $date = date("Y-m-d");

        return mysqli_query($this->connection, "INSERT INTO 
                                                        users (username, firstName, lastName, email, password, signUpDate, profilePic) 
                                                        VALUES ('$username', '$firstName', '$lastName', '$email', '$encryptedPassword', '$date', '$profilePic')");
    }

    //Check if username is valid
    private function validateUsername($username)
    {
        if (strlen($username) > 25 || strlen($username) < 5) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }

        //Check if username exists
        $checkUsernameQuery = mysqli_query($this->connection, "SELECT username FROM users WHERE username = '$username'");
        if (mysqli_num_rows($checkUsernameQuery) != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
            return;
        }
    }

    //Check if first name is valid
    private function validateFirstName($firstName)
    {
        if (strlen($firstName) > 25 || strlen($firstName) < 2) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
            return;
        }
    }

    //Check if last name is valid
    private function validateLastName($lastName)
    {
        if (strlen($lastName) > 25 || strlen($lastName) < 2) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
            return;
        }
    }

    //Check if email is valid
    private function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        //Check if email exists
        $checkEmailQuery = mysqli_query($this->connection, "SELECT username FROM users WHERE email = '$email'");
        if (mysqli_num_rows($checkEmailQuery) != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
            return;
        }
    }

    //Check if password is valid
    private function validatePassword($password, $password2)
    {
        if ($password != $password2) {
            array_push($this->errorArray, Constants::$passwordsDoNotMatch);
            return;
        }

        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($this->errorArray, Constants::$passwordsNotAlphanumeric);
            return;
        }

        if (strlen($password) > 30 || strlen($password) < 5) {
            array_push($this->errorArray, Constants::$passwordCharacters);
            return;
        }
    }

    //Check if login is success or not
    public function login($username, $password) {
        $password = md5($password);
        $query = mysqli_query($this->connection, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");

        if (mysqli_num_rows($query) == 1) {
            return true;
        } else {
            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
    }

}