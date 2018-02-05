<?php
namespace alesw\classes;
use PDO;

class Tweet {
  private $idt;
  private $ida;
  private $created_at;
  private $text;
  private $retweet_count;
  private $favorite_count;
  private $pdo;

  public function __construct() {
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

  public function maxTweetId($ida) {
    $this->connectToDb();
    $sql = "SELECT MAX(idt) AS maxid FROM tweet WHERE ida='$ida'";
    $statement = $this->pdo->query($sql);
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$row['maxid'])
      return 1;
    return $row['maxid'];
  }

  public function getIda() {
    return $this->ida;
  }

  public function getIdt() {
    return $this->idt;  
  }

  public function getCreatedAt() {
    return $this->created_at;  
  }

  public function getText() {
    return $this->text;  
  }

  public function getRetweetCount() {
    return $this->retweet_count;  
  }

  public function getFovoriteCount() {
    return $this->favorite_count;
  }

  public function setIda($ida) {
    $this->ida = intval($ida);
  }

  public function setIdt($idt) {
    $this->idt = intval($idt);  
  }

  public function setCreatedAt($created_at) {
    $this->created_at = $created_at; 
  }

  public function setText($text) {
    $this->text = $text; 
  }

  public function setRetweetCount($retweet_count) {
    $this->retweet_count = intval($retweet_count);
  }

  public function setFovoriteCount($favorite_count) {
    $this->favorite_count = intval($favorite_count);
  }
}
?>