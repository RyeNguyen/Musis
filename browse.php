<?php

include("includes/includedFiles.php");

?>

<h1 class="pageHeadingBig">Có thể bạn sẽ thích</h1>

<div class="gridViewContainer">

    <?php
    $albumQuery = mysqli_query($connection, "SELECT * FROM albums ORDER BY RAND()");

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