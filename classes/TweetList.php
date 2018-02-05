<?php
namespace alesw\classes;
use alesw\classes\Tweet;
use PDO;
use Abraham\TwitterOAuth\TwitterOAuth;

class TweetList {
  private $pdo;
  private $tweetList;

  public function __construct() {
    $this->tweetList = array();
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


  public function getTweetsFromDb($ida) {
    $this->tweetList = array();
    $this->connectToDb();
    $ida =intval($ida);

    $statement = $this->pdo->query("SELECT * FROM tweet WHERE ida=$ida ORDER BY posted DESC");
    while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $tweet = new Tweet();
        $tweet->setIda($row['ida']);
        $tweet->setIdt($row['idt']);
        $tweet->setCreatedAt($row['posted']);
        $tweet->setText($row['text']);
        $tweet->setRetweetCount( $row['retweets']);
        $tweet->setFovoriteCount($row['likes']);
        $this->tweetList[] = $tweet;
    }
    return $this->tweetList;
  }  


  public function getTweetsFromTwitter($screen_name, $month = 1, $year = 2018, $since_id = 1) {
    $i = 1;
    $count = 50;
    $max_id = -1;
    $this->tweetList = array();

    $params = array("screen_name" => $screen_name, "count" => $count, "since_id" => "$since_id");
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, access_token, access_token_secret);

    do {
      $content = $connection->get("statuses/user_timeline", $params);
      // if Twitter returns error message
      if (is_object($content) && isset($content->errors)) {
        echo($content->errors[0]->message) . '<br />';
        break;
      }
      else if (empty($content)) {  // no new tweets to process
        break;
      }

      foreach ($content as $tweets => $tweet) {
        $tweetObj = new Tweet();
        $tweet_date = strtotime($tweet->created_at);  // Wed Aug 27 13:08:45 +0000 2008
        $cet_time = $tweet_date + date('Z');
        $date = date("Y-m-d H:i", $cet_time);         // 'YYYY-MM-DD HH:MM:SS'
        $tweetObj->setCreatedAt($date);
        $tweetObj->setIdt($tweet->id);
        $tweetObj->setText($tweet->text);
        $tweetObj->setRetweetCount($tweet->retweet_count);
        $tweetObj->setFovoriteCount($tweet->favorite_count);
        $tweetObj->setIda($tweet->user->id);

        if (!$since_id)
          $since_id = $tweet->id;    
        
        $tweet_month = date('n', $cet_time);
        $tweet_year = date('Y', $cet_time);
        $max_id = $tweet->id;

        if ($tweet_year < $year || ($tweet_year == $year && $tweet_month < $month)) {
          $i = -1;
          break;
        }
        else if ($tweet_year == $year && $tweet_month > $month)
          continue;
        else
          $this->tweetList[] = $tweetObj;
      }
      $max_id--;
      $params = array("screen_name" => $screen_name, "count" => $count, "max_id" => $max_id, "since_id" => "$since_id");
    } while ($i > 0); 
  }  


  public function saveToDb() {
    $tmp = array();
    $this->connectToDb();

    foreach($this->tweetList as $tweet) {
      $ida = intval($tweet->getIda());
      $idt = intval($tweet->getIdt());
      $posted = $tweet->getCreatedAt();
      $text = htmlspecialchars($tweet->getText(), ENT_QUOTES);
      $retweets = intval($tweet->getRetweetCount());
      $likes = intval($tweet->getFovoriteCount());
      $tmp[] = "('$ida', '$idt', '$posted', '$text', '$retweets', '$likes')";
    }

    if (!empty($tmp)) {
      $values = implode(", ", $tmp);
      $sql = "INSERT INTO tweet (ida, idt, posted, text, retweets, likes) VALUES $values";
      $statement = $this->pdo->query($sql);      
    }
  }


  public function sumOfLikes($ida) {
    $this->connectToDb();
    $ida = intval($ida);
    $sql = "SELECT SUM(likes) as sumlikes FROM tweet WHERE ida='$ida'";
    $statement = $this->pdo->query($sql);
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$row['sumlikes'])
      return 1;
    return $row['sumlikes'];
  }  

  public function getTweetList() {
    return $this->tweetList;  
  }     
}