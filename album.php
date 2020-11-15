<?php include("includes/includedFiles.php");

if (isset($_GET['id'])) {
    $albumId = $_GET['id'];
} else {
    header("Location: index.php");
}

$album = new Album($connection, $albumId);

$artist = $album->getArtist();

?>

<div class="entityInfo">

    <div class="leftSection">
        <img src="<?php echo $album->getArtworkPath(); ?>" alt="Album Artwork">
    </div>

    <div class="rightSection">
        <h2><?php echo $album->getTitle(); ?></h2>
        <p>Nghệ sĩ: <?php echo $artist->getName(); ?></p>
        <p><?php echo $album->getNumberOfSongs(); ?> bài hát</p>
    </div>

</div>

<div class="trackListContainer">

    <ul class="trackList">

        <?php

            $songIdArray = $album->getSongId();

            $i = 1;
            foreach($songIdArray as $songId) {
                $albumSong = new Song($connection, $songId);
                $albumArtist = $albumSong->getArtist();

                echo "<li class='trackListRow'>
                    <div class='trackCount'>
                        <img class='play' src='assets/images/icons/play_button_circled.svg' alt='play button' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)'>
                        <span class='trackNumber'>$i</span>
                    </div>
                    
                    <div class='trackInfo'>
                        <span class='trackName'>" . $albumSong->getTitle() . "</span>
                        <span class='artistName'>" . $albumArtist->getName() . "</span>
                    </div>
                    
                    <div class='trackOption'>
                        <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                        <img class='optionButton' src='assets/images/icons/more.svg' alt='Option Button' onclick='showOptionsMenu(this)'>
                    </div>
                    
                    <div class='trackDuration'>
                        <span class='duration'>" . $albumSong->getDuration() . "</span>
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

<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <?php echo Playlist::getPlaylistDropdown($connection, $userLoggedIn->getUsername()); ?>
    <div class="item">Option</div>
</nav>