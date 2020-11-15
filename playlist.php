<?php include("includes/includedFiles.php");

if (isset($_GET['id'])) {
    $playlistId = $_GET['id'];
} else {
    header("Location: index.php");
}

$playlist = new Playlist($connection, $playlistId);
$owner = new User($connection, $playlist->getOwner());

?>

<div class="entityInfo">

    <div class="leftSection">
        <div class="playlistImage">
            <img src="assets/images/icons/#" alt="Playlist Artwork">
        </div>
    </div>

    <div class="rightSection">
        <h2><?php echo $playlist->getName(); ?></h2>
        <p>của <?php echo $playlist->getOwner(); ?></p>
        <p><?php echo $playlist->getNumberOfSongs(); ?> bài hát</p>
        <button class="button" onclick="deletePlaylist('<?php echo $playlistId; ?>')">Xoá danh sách phát</button>
    </div>

</div>

<div class="trackListContainer">

    <ul class="trackList">

        <?php

        $songIdArray = $playlist->getSongId();

        $i = 1;
        foreach($songIdArray as $songId) {
            $playlistSong = new Song($connection, $songId);
            $songArtist = $playlistSong->getArtist();

            echo "<li class='trackListRow'>
                    <div class='trackCount'>
                        <img class='play' src='assets/images/icons/play_button_circled.svg' alt='play button' onclick='setTrack(\"" . $playlistSong->getId() . "\", tempPlaylist, true)'>
                        <span class='trackNumber'>$i</span>
                    </div>
                    
                    <div class='trackInfo'>
                        <span class='trackName'>" . $playlistSong->getTitle() . "</span>
                        <span class='artistName'>" . $songArtist->getName() . "</span>
                    </div>
                    
                    <div class='trackOption'>
                        <img class='optionButton' src='assets/images/icons/' alt='Option Button' style='width: 15px; visibility: hidden'>
                    </div>
                    
                    <div class='trackDuration'>
                        <span class='duration'>" . $playlistSong->getDuration() . "</span>
                    </div>
                    
                </li>";

            $i++;
        }

        ?>

        <script>
            let tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds);
        </script>

    </ul>

</div>