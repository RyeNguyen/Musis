<?php

include("../../config.php");

if (isset($_POST['playlistId']) && isset($_POST['songId'])) {
    $playlistId = $_POST['playlistId'];
    $songId = $_POST['songId'];

    $orderIdQuery = mysqli_query($connection, "SELECT MAX(playlistOrder) + 1 AS playlistOrder FROM playlistsongs WHERE playlistId='$playlistId'");
    $row = mysqli_fetch_array($orderIdQuery);
    $order = $row['playlistOrder'];

    $query = mysqli_query($connection, "INSERT INTO playlistsongs (songId, playlistId, playlistOrder) VALUE ('$songId', '$playlistId', '$order')");
} else {
    echo "PlaylistId or songId was not passed into addToPlaylist.php";
}