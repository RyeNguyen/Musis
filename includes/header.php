<?php
include("includes/config.php");
include("includes/classes/User.php");
include("includes/classes/Artist.php");
include("includes/classes/Album.php");
include("includes/classes/Song.php");
include("includes/classes/Playlist.php");

//session_destroy(): LOGOUT

if (isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = new User($connection, $_SESSION['userLoggedIn']);
    $username = $userLoggedIn->getUsername();
    echo "<script>
            userLoggedIn = '$username';
            </script>";
} else {
    header("Location: register.php");
}

?>



<html lang="vi">

<head>
    <title>Music Player!</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <link rel="icon" type="image/svg" href="assets/images/icons/musical_notes.svg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>

<body>

<div id="mainContainer">

    <div id="topContainer">

        <?php include("includes/navBarContainer.php"); ?>

        <div id="mainViewContainer">

            <div id="mainContent">