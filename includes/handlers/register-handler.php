<?php

function sanitizeFormPassword($input)
{
    $input = strip_tags($input);
    return $input;
}

function sanitizeFormUsername($input)
{
    $input = strip_tags($input); //Get rid of html elements
    $input = str_replace(" ", "", $input);
    return $input;
}

function sanitizeFormString($input)
{
    $input = strip_tags($input); //Get rid of html elements
    $input = str_replace(" ", "", $input);
    $input = ucFirst(strtolower($input)); //Make the first char uppercase
    return $input;
}

if (isset($_POST['registerButton'])) {
    $username = sanitizeFormUsername($_POST['username']);
    $firstName = sanitizeFormString($_POST['firstName']);
    $lastName = sanitizeFormString($_POST['lastName']);
    $email = sanitizeFormString($_POST['email']);
    $password = sanitizeFormPassword($_POST['password']);
    $password2 = sanitizeFormPassword($_POST['password2']);

    $wasSuccessful = $account->register($username, $firstName, $lastName, $email, $password, $password2);

    if ($wasSuccessful) {
        $_SESSION['userLoggedIn'] = $username;
        header("Location: index.php");
    }
}