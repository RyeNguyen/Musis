<?php
    ob_start(); //Turn on output buffering
    session_start(); //Enable the use of sessions

    $timezone = date_default_timezone_set("Asia/Ho_Chi_Minh");

    $connection = mysqli_connect("localhost", "root", "0973642872mh", "music_site");

//    $connection = mysqli_connect("sql12.freemysqlhosting.net", "sql12377917", "Jc8sznCsCg", "sql12377917");

    if (mysqli_connect_errno()) {
        echo "Failed to connect: " . mysqli_connect_errno();
    }