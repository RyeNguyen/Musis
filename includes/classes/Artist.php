<?php

class Artist
{

    private $connection;
    private $id;

    public function __construct($connection, $id)
    {
        $this->connection = $connection;
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        $artistQuery = mysqli_query($this->connection, "SELECT name FROM artists WHERE id='$this->id'");
        $artist = mysqli_fetch_array($artistQuery);
        return $artist['name'];
    }

    public function getSongId() {
        $query = mysqli_query($this->connection, "SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays DESC");
        $array = array();

        while ($row = mysqli_fetch_array($query)) {
            array_push($array, $row['id']);
        }

        return $array;
    }

}