<?php

include("includes/includedFiles.php");

if (isset($_GET['term'])) {
    $term = urldecode($_GET['term']);
} else {
    $term = "";
}

?>

<div class="searchContainer">

    <h4>Tìm kiếm nghệ sĩ, album, bài hát</h4>
    <label>
        <input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="Nhập ở đây...">
    </label>

</div>

<script>

    $(document).ready(() => {
        const searchInput = $(".searchInput");

        searchInput.focus();
        const search = searchInput.val();
        searchInput.val('');
        searchInput.val(search);
    })

    $(function() {

        $(".searchInput").keyup(() => {

            //Cancel the timer if the user hasn't finish typing
            clearTimeout(timer);

            timer = setTimeout(() => {
                const val = $(".searchInput").val();
                openPage("search.php?term=" + val);
            }, 2000)
        })
    })

    $(document).ready(() => {
        const searchInput = $(".searchInput");

        searchInput.focus();
        const search = searchInput.val();
        searchInput.val('');
        searchInput.val(search);
    })

</script>

<?php if($term == "") exit(); ?>

<div class="trackListContainer">
    <h2>Bài hát</h2>
    <ul class="trackList">

        <?php
        $songsQuery = mysqli_query($connection, "SELECT id FROM songs WHERE title LIKE '%$term%' LIMIT 10");

        if (mysqli_num_rows($songsQuery) == 0) {
            echo "<span class='noResults'>Không tìm thấy bài hát khớp với \"" . $term . "\"</span>";
        }

        $songIdArray = array();

        $i = 1;
        while($row = mysqli_fetch_array($songsQuery)) {

            if ($i > 15) {
                break;
            }

            array_push($songIdArray, $row['id']);

            $albumSong = new Song($connection, $row['id']);
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

<div class="gradientLine"></div>

<div class="artistsContainer">

    <h2>Nghệ sĩ</h2>

    <?php

    $artistsQuery = mysqli_query($connection, "SELECT id FROM artists WHERE name LIKE '%$term%' LIMIT 10");

    if (mysqli_num_rows($artistsQuery) == 0) {
        echo "<span class='noResults'>Không tìm thấy nghệ sĩ nào có tên giống với \"" . $term . "\"</span>";
    }

    while($row = mysqli_fetch_array($artistsQuery)) {
        $artistFound = new Artist($connection, $row['id']);

        echo "<div class='searchResultRow'>

                <div class='artistName'>
                
                    <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" .$artistFound->getId() . "\")'>"

                        . $artistFound->getName() .

                    "</span>
                
                </div>
    
            </div>";
    }
    
    ?>

</div>


<div class="gradientLine below"></div>

<div class="gridViewContainer">
    <h2>Album</h2>
    <?php
    $albumQuery = mysqli_query($connection, "SELECT * FROM albums WHERE title LIKE '%$term%' LIMIT 10");

    if (mysqli_num_rows($albumQuery) == 0) {
        echo "<span class='noResults'>Không tìm thấy album nào khớp với \"" . $term . "\"</span>";
    }

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