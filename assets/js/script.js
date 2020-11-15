let currentPlaylist = [];
let shufflePlaylist = [];
let tempPlaylist = [];
let currentIndex = 0;
let timer;

$(document).click((click) => {
    const target = $(click.target);

    if (!target.hasClass('item') && !target.hasClass('optionButton')) {
        hideOptionsMenu();
    }
})

$(window).scroll(() => {
    hideOptionsMenu();
})

$(document).on("change", "select.playlist", () => {
    const playlistId = $(this).val();
    const songId = $(this).prev(".songId").val();

    $.post("includes/handlers/ajax/addToPlaylist.php", {playlistId: playlistId, songId: songId}).done((error) => {

        if (error) {
            alert(error);
            return;
        }

        // hideOptionsMenu();
        // select.val("");
    })
})

function openPage(url) {

    if (timer != null) {
        clearTimeout(timer);
    }

    if (url.indexOf("?") === -1) {
        url = url + "?";
    }

    const encodedUrl = url + "&userLoggedIn=" + userLoggedIn;
    $("#mainContent").load(encodedUrl);

    //Scroll to the top when reloading the page
    $("body").scrollTop(0);

    //Changing the url without reloading the whole page
    history.pushState(null, null, url);
}

function createPlaylist() {
    const popup = prompt("Nhập tên danh sách phát của bạn");

    if (popup != null) {
        $.post("includes/handlers/ajax/createPlaylist.php", {name: popup, username: userLoggedIn}).done((error) => {

            if (error !== "") {
                alert(error);
                return;
            }

            openPage("yourMusic.php");
        })
    }
}

function deletePlaylist(playlistId) {
    const prompt = confirm("Bạn có chắc muốn xoá danh sách này?");

    if (prompt) {
        $.post("includes/handlers/ajax/deletePlaylist.php", {playlistId}).done((error) => {

            if (error !== "") {
                alert(error);
                return;
            }

            openPage("yourMusic.php");
        })
    }
}

function showOptionsMenu(button) {
    const songId = $(button).prevAll(".songId").val();
    const menu = $(".optionsMenu");
    menu.find(".songId").val(songId);

    const scrollTop = $(window).scrollTop(); //Distance from top of window to top of document
    const elementOffset = $(button).offset().top; //Distance from top of document

    const top = elementOffset - scrollTop;
    const left = $(button).position().left;

    menu.css({ "top": top + "px", "left": left + "px", "display": "inline" });
}

function hideOptionsMenu() {
    const menu = $(".optionsMenu");
    if (menu.css("display") !== "none") {
        menu.css("display", "none");
    }
}

//Format the time to be minutes:seconds
function formatTime(seconds) {
    const time = Math.round(seconds);
    const minutes = Math.floor(time / 60);
    seconds = time - minutes * 60;

    let extraZero = (seconds < 10) ? "0" : "";

    return minutes + ":" + extraZero + seconds;
}

//Update the progress bar when the song is playing in real time
function updateTimeProgressBar(audio) {
    $(".progressTime.current").text(formatTime(audio.currentTime));
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

    let progress = audio.currentTime / audio.duration * 100;
    $(".playbackBar .progress").css("width", progress + "%");
}

//Update the volume bar when the user interacts with it
function updateVolumeProgressBar(audio) {
    let volume = audio.volume * 100;
    $(".volumeBar .progress").css("width", volume + "%");
}

function playFirstSong() {
    setTrack(tempPlaylist[0], tempPlaylist, true);
}

function Audio() {

    this.currentlyPlaying = null;
    this.audio = document.createElement("audio");

    this.audio.addEventListener("ended", () => {
        nextSong();
    });

    //Set the source of the song
    this.setTrack = (track) => {
        this.currentlyPlaying = track;
        this.audio.src = track.path;
    }

    this.play = () => {
        this.audio.play().then(r => {
        });
    }

    this.pause = () => {
        this.audio.pause();
    }

    this.setTime = (seconds) => {
        this.audio.currentTime = seconds;
    }

}