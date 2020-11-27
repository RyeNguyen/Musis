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

$(document).on("change", "select.playlist", function() {
    const select = $(this);

    const playlistId = select.val();
    const songId = select.prev(".songId").val();

    console.log("playlistId: " + playlistId);
    console.log("songId: " + songId);

    $.post("includes/handlers/ajax/addToPlaylist.php", {playlistId, songId}).done(function(error) {

        if (error) {
            alert(error);
            return;
        }

        hideOptionsMenu();
        select.val("");
    })
})

//Open page when users click on links
function openPage(url) {

    if (timer != null) {
        clearTimeout(timer);
    }

    if (url.indexOf("?") === -1) {
        url = url + "?";
    }

    let encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);

    //Check if there are too much spaces
    if (encodedUrl.includes("%25")) {
        encodedUrl = url + "&userLoggedIn=" + userLoggedIn;
    }
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

function logout() {
    $.post("includes/handlers/ajax/logout.php", () => {
        location.reload();
    })
}

function updateEmail(emailClass) {
    const emailValue = $("." + emailClass).val();

    $.post("includes/handlers/ajax/updateEmail.php", { email: emailValue, username: userLoggedIn }).done(response => {
        $("." + emailClass).nextAll(".message").text(response);
    })
}

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
    const oldPassword = $("." + oldPasswordClass).val();
    const newPassword1 = $("." + newPasswordClass1).val();
    const newPassword2 = $("." + newPasswordClass2).val();

    $.post("includes/handlers/ajax/updatePassword.php",
        { oldPassword, newPassword1, newPassword2, username: userLoggedIn }).done(response => {
        $("." + oldPasswordClass).nextAll(".message").text(response);
    })
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
        this.audio.play().then(r => {});
    }

    this.pause = () => {
        this.audio.pause();
    }

    this.setTime = (seconds) => {
        this.audio.currentTime = seconds;
    }

}