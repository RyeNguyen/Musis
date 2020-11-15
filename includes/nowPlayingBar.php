<?php

//Query all the songs randomly
$songQuery = mysqli_query($connection, "SELECT id FROM songs ORDER BY RAND()");

//Array of songs
$resultArray = array();

//Loop over all the songs and push them into array
while ($row = mysqli_fetch_array($songQuery)) {
    array_push($resultArray, $row['id']);
}

//Convert the array into json to support Javascript
$jsonArray = json_encode($resultArray);

?>


<script>

    let repeat = false;
    let shuffle = false;
    let mouseDown = false;
    let audioElement = null;

    //Start this as soon as the page loads
    $(document).ready(() => {

        //currentPlaylist now contains the songs array
        let newPlaylist = <?php echo $jsonArray; ?>;

        //Create an audio element from class Audio in script.js
        audioElement = new Audio();

        //Show the song's duration to the UI
        audioElement.audio.addEventListener("canplay", () => {
            let duration = formatTime(audioElement.audio.duration);
            $(".progressTime.remaining").text(duration);
        });

        //Update the progress bar in real time
        audioElement.audio.addEventListener("timeupdate", () => {
            if (audioElement.audio.duration) {
                updateTimeProgressBar(audioElement.audio);
            }
        });

        //Update the volume bar in real time
        audioElement.audio.addEventListener("volumechange", () => {
            updateVolumeProgressBar(audioElement.audio);
        });

        setTrack(newPlaylist[0], newPlaylist, false);

        //Update the volume bar to be 100% when the page loads
        updateVolumeProgressBar(audioElement.audio);

        //Prevent users from highlighting the icons
        $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", (e) => {
            e.preventDefault();
        })


        //Events when user interacts with the playback bar
        const playbackBar = $(".playbackBar .progressBar");

        playbackBar.mousedown(() => {
            mouseDown = true;
        });

        playbackBar.mousemove((e) => {
            if (mouseDown) {
                //Set time of song depending on position of mouse
                timeFromOffset(e, playbackBar);
            }
        });

        playbackBar.mouseup((e) => {
            timeFromOffset(e, playbackBar);
        });


        //Events when user interacts with the volume bar
        const volumeBar = $(".volumeBar .progressBar");

        volumeBar.mousedown(() => {
            mouseDown = true;
        });

        volumeBar.mousemove((e) => {
            if (mouseDown) {
                const percentage = e.offsetX / $(volumeBar).width();
                //Volume only accepts values from 0 to 1
                if (percentage >= 0 && percentage <= 1) {
                    audioElement.audio.volume = percentage;
                }
            }
        });

        volumeBar.mouseup((e) => {
            const percentage = e.offsetX / $(volumeBar).width();
            //Volume only accepts values from 0 to 1
            if (percentage >= 0 && percentage <= 1) {
                audioElement.audio.volume = percentage;
            }
        });

        //Stop the interactions when user releases the mouse
        $(document).mouseup(() => {
            mouseDown = false;
        });
    });

    //Calculate the time of the song from the offset of the mouse
    function timeFromOffset(mouse, progressBar) {
        const percentage = mouse.offsetX / $(progressBar).width() * 100;
        const seconds = audioElement.audio.duration * (percentage / 100);
        audioElement.setTime(seconds);
    }

    //Handles skipping to the previous song
    function prevSong() {
        if (audioElement.audio.currentTime >= 3 || currentIndex === 0) {
            audioElement.setTime(0);
        } else {
            currentIndex--;
            setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
        }
    }

    //Handles skipping to the next song
    function nextSong() {

        //If repeat is on then the song will be looped from the beginning
        if (repeat) {
            audioElement.setTime(0);
            playSong();
            return;
        }

        if (currentIndex === currentPlaylist.length - 1) {
            currentIndex = 0;
        } else {
            currentIndex++;
        }

        let trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
        setTrack(trackToPlay, currentPlaylist, true);
    }

    //When repeat is on
    function setRepeat() {
        repeat = !repeat;
        let imageName = repeat ? "repeat_active.svg" : "repeat.svg";
        $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
    }

    //When muted is on
    function setMute() {
        audioElement.audio.muted = !audioElement.audio.muted;
        let imageName = audioElement.audio.muted ? "no_audio.svg" : "audio.svg";
        $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
    }

    function setShuffle() {
        shuffle = !shuffle;
        let imageName = shuffle ? "shuffle_active.svg" : "shuffle.svg";
        $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);


        if (shuffle) {
            //randomize Playlist
            shuffleArray(shufflePlaylist);
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
        } else {
            //Go back to regular playlist
            currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
        }
    }

    //Algorithm to shuffle elements in an array
    function shuffleArray(a) {
        let j, x, i;
        for (i = a.length; i; i--) {
            j = Math.floor(Math.random() * i);
            x = a[i - 1];
            a[i - 1] = a[j];
            a[j] = x;
        }
    }

    function setTrack(trackId, newPlaylist, play) {

        if (newPlaylist !== currentPlaylist) {
            currentPlaylist = newPlaylist;
            shufflePlaylist = currentPlaylist.slice();
            shuffleArray(shufflePlaylist);
        }

        if (shuffle) {
            currentIndex = shufflePlaylist.indexOf(trackId);
        } else {
            currentIndex = currentPlaylist.indexOf(trackId);
        }
        pauseSong();

        //Use Ajax calls to get the data (songs) from the database
        $.post("includes/handlers/ajax/getSongJson.php", {songId: trackId}, function (data) {

            //Save the data from Ajax calls to the track JSON variable
            const track = JSON.parse(data);

            //Show the song's title from the track variable to the UI
            $(".trackName span").text(track.title);

            //Use Ajax calls to get the data (artist id) from the database
            $.post("includes/handlers/ajax/getArtistJson.php", {artistId: track.artist}, function (data) {
                const artist = JSON.parse(data);

                //Show the song's artist name from the artist variable to the UI
                const artistName = $(".trackInfo .artistName span");
                artistName.text(artist.name);
                artistName.attr("onclick", "openPage('artist.php?id=" + artist.id + " ')");
            });

            //Use Ajax calls to get the data (album id) from the database
            $.post("includes/handlers/ajax/getAlbumJson.php", {albumId: track.album}, function (data) {
                const album = JSON.parse(data);

                //Show the song's album artwork from the album variable to the UI
                const albumLink = $(".content .albumLink img");
                albumLink.attr("src", album.artworkPath);
                albumLink.attr("onclick", "openPage('album.php?id=" + album.id + " ')");
                $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id=" + album.id + " ')");
            });

            //Set the source of the audio in script.js
            audioElement.setTrack(track);

            if (play) {
                playSong();
            }
        });
    }

    //Play the song when clicking the play button
    function playSong() {

        //Update the song's play count only when the song is played from the beginning
        if (audioElement.audio.currentTime === 0) {
            $.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id});
        }

        $(".controlButton.play").hide();
        $(".controlButton.pause").show();
        audioElement.play();
    }

    //Pause the song when clicking the pause button
    function pauseSong() {
        $(".controlButton.play").show();
        $(".controlButton.pause").hide();
        audioElement.pause();
    }

</script>


<div id="nowPlayingBarContainer">

    <div id="nowPlayingBar">

        <div id="nowPlayingLeft">

            <div class="content">

                <span class="albumLink">
                    <img role="link" tabindex="0" src="" class="albumArtwork" alt="Album artwork">
                </span>

                <div class="trackInfo">

                        <span class="trackName">
                            <span role="link" tabindex="0"></span>
                        </span>

                        <span class="artistName">
                            <span role="link" tabindex="0"></span>
                        </span>

                </div>

            </div>

        </div>

        <div id="nowPlayingCenter">

            <div class="content playerControls">

                <div class="buttons">

                    <button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
                        <img src="assets/images/icons/shuffle.svg" alt="Shuffle" style="width: 32px">
                    </button>

                    <button class="controlButton previous" title="Previous button" onclick="prevSong()">
                        <img src="assets/images/icons/skip_to_start.svg" alt="Previous" style="width: 32px">
                    </button>

                    <button class="controlButton play" title="Play button" onclick="playSong()">
                        <img src="assets/images/icons/play_button_circled.svg" alt="Play" style="width: 40px">
                    </button>

                    <button class="controlButton pause" title="Pause button" style="display: none"
                            onclick="pauseSong()">
                        <img src="assets/images/icons/pause.svg" alt="Pause" style="width: 40px">
                    </button>

                    <button class="controlButton next" title="Next button" onclick="nextSong()">
                        <img src="assets/images/icons/end.svg" alt="Next" style="width: 32px">
                    </button>

                    <button class="controlButton repeat" title="Repeat button" onclick="setRepeat()">
                        <img src="assets/images/icons/repeat.svg" alt="Repeat" style="width: 32px">
                    </button>

                </div>

                <div class="playbackBar">

                    <span class="progressTime current">0.00</span>

                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress">

                            </div>
                        </div>
                    </div>

                    <span class="progressTime remaining">0.00</span>

                </div>

            </div>

        </div>

        <div id="nowPlayingRight">

            <div class="volumeBar">

                <button class="controlButton volume" title="Volume button" onclick="setMute()">
                    <img src="assets/images/icons/audio.svg" alt="Volume" style="width: 32px; margin-bottom: 7px;">
                </button>

                <div class="progressBar">
                    <div class="progressBarBg">
                        <div class="progress">

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>