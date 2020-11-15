<?php

include("includes/includedFiles.php");

?>

<div class="playlistContainer">

    <div class="gridViewContainer">

        <h2>Danh sách phát</h2>

        <div class="buttonItems">
            <button class="button" onclick="createPlaylist()">Danh sách phát mới</button>
        </div>

        <?php
        $username = $userLoggedIn->getUsername();

        $playlistsQuery = mysqli_query($connection, "SELECT * FROM playlists WHERE owner='$username'");

        if (mysqli_num_rows($playlistsQuery) == 0) {
            echo "<span class='noResults'>Bạn chưa có danh sách phát nào.</span>";
        }

        while ($row = mysqli_fetch_array($playlistsQuery)) {

            $playlist = new Playlist($connection, $row);

            echo "<div class='gridViewItem' role='link' tabindex='0' 
            onclick='openPage(\"playlist.php?id=" . $playlist->getId() . "\")'>

                    <div class='playlistImage'>
                        <img src='assets/images/icons/#' alt='playlist image'>
                    </div>

                    <div class='gridViewInfo'>
                        " . $playlist->getName() . "
                    </div>
                  </div>";

        }
        ?>

    </div>

</div>