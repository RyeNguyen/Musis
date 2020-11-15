<?php

include("../../config.php");

//Query the songs from the database to an array
if (isset($_POST['songId'])) {
    $songId = $_POST['songId'];

    $query = mysqli_query($connection, "SELECT * FROM songs WHERE id='$songId'");

    $resultArray = mysqli_fetch_array($query);

    echo json_encode($resultArray);
}