<?php
namespace alesw\classes;
use alesw\classes\Artist;
use PDO;

class ArtistList {
  private $pdo;
  private $artistList;

  public function __construct() {
    $this->artistList = array();
    $this->connectToDb();
    $this->getArtists();
  }

  public function __destruct() {
    $this->pdo = null;
  } 

  private function connectToDb() {
    try {
      $this->pdo = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PWD);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (\PDOException $e) {
      $output = 'Unable to connect to the database server: ' . $e;
      echo $output;
    }  
  }  


  public function getArtists() {
    $this->artistList = array();

    $statement = $this->pdo->query('SELECT * FROM artist');
    while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $artist = new Artist();
        $artist->setId($row['ida']);
        $artist->setScreenName($row['screen_name']);
        $artist->setFollowersCount($row['followers']);
        $artist->setStatusesCount($row['tweets']);
        $this->artistList[] = $artist;
    }
    return $this->artistList;
  }  

  public function getArtistList() {
    return $this->artistList;  
  }   
}