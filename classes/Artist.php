<?php
namespace alesw\classes;
use Abraham\TwitterOAuth\TwitterOAuth;
use PDO;

class Artist {
  private $id;
  private $screen_name;
  private $followers_count;
  private $statuses_count;
  private $pdo;


  public function __construct($screen_name = null) {
    if ($screen_name)
      $this->loadArtistFromTwitter($screen_name);
  }

  public function __destruct() {
    $this->pdo = null;
  }  

  public function loadArtistFromTwitter($screen_name) {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, access_token, access_token_secret);
    $content = $connection->get("users/show", ["screen_name" => $screen_name]);
    $this->setId($content->id);
    $this->setScreenName($content->screen_name);
    $this->setFollowersCount($content->followers_count); 
    $this->setStatusesCount($content->statuses_count);
  }

  public function loadArtistFromDb($screen_name) {
    $this->connectToDb();

    $statement = $this->pdo->query("SELECT * FROM artist WHERE screen_name=$screen_name");
    while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $this->setId($content->id);
      $this->setName($content->name);
      $this->setScreenName($content->screen_name);
      $this->setFollowersCount($content->followers_count); 
      $this->setStatusesCount($content->statuses_count);
    }
  }


  function connectToDb() {
    try {
      $this->pdo = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PWD);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (\PDOException $e) {
      $output = 'Unable to connect to the database server: ' . $e;
      echo $output;
    }  
  }

  public function saveToDb($update = true) {
    $ida = intval($this->getId());
    $screen_name = htmlspecialchars($this->getScreenName(), ENT_QUOTES);
    $followers = intval($this->getFollowersCount());
    $tweets = intval($this->getStatusesCount());
    $this->connectToDb();
    if ($update)
      $sql = "UPDATE artist SET followers='$followers', tweets='$tweets' WHERE screen_name='$screen_name'";
    else
      $sql = "UPDATE artist SET ida='$ida', followers='$followers', tweets='$tweets' WHERE screen_name='$screen_name'";
    $statement = $this->pdo->query($sql);    
  }  

  public function getId() {
    return $this->id;
  }

  public function getScreenName() {
    return $this->screen_name;
  }

  public function getFollowersCount() {
    return $this->followers_count;
  }

  public function getStatusesCount() {
    return $this->statuses_count;
  }

  public function setId($id) {
    $this->id = intval($id);
  }

  public function setScreenName($screen_name) {
    $this->screen_name = $screen_name;
  }

  public function setFollowersCount($followers_count) {
    $this->followers_count = intval($followers_count);
  }

  public function setStatusesCount($statuses_count) {
    $this->statuses_count = intval($statuses_count);
  }
}
?>