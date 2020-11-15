<?php

include("includes/includedFiles.php");

if (isset($_GET['id'])) {
    $artistId = $_GET['id'];
} else {
    header("Location: index.php");
}

$artist = new Artist($connection, $artistId);

?>

<div class="entityInfo">
    <div class="centerSection">
        <div class="artistInfo">
            <h1 class="artistName"><?php echo $artist->getName(); ?></h1>

            <div class="headerButton">
                <button class="button" onclick="playFirstSong()">PLAY</button>
            </div>
        </div>
    </div>
</div>

<div class="gradientLine"></div>

<div class="trackListContainer">
    <h2>Bài hát nổi bật</h2>
    <ul class="trackList">

        <?php

        $songIdArray = $artist->getSongId();

        $i = 1;
        foreach($songIdArray as $songId) {

            if ($i > 10) {
                break;
            }

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
                        <img class='optionButton' src='assets/images/icons/' alt='Option Button' style='width: 15px; visibility: hidden'>
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

<div class="gradientLine below"></div>

<div class="gridViewContainer">
    <h2>Album</h2>
    <?php
    $albumQuery = mysqli_query($connection, "SELECT * FROM albums WHERE artist='$artistId'");

    while ($row = mysqli_fetch_array($albumQuery)) {

        echo "<div class='gridViewItem'>
                    <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
                        <img src=" . $row['artworkPath'] . " alt='Artwork'>
                
                        <div class='gridViewInfo'>
                            " . $row['title'] . "
                        </div>
                    </span>
                  </div>";

    }
    ?>

</div>

