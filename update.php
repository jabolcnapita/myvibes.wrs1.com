<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "api-credentials.php";
require_once "db-config.php";
require_once "libs/twitteroauth/autoload.php";
require_once "autoload.php";

use alesw\classes\Artist;
use alesw\classes\ArtistList;
use alesw\classes\Tweet;
use alesw\classes\TweetList;

$month = 1;
$year = 2018;
if (!empty($_GET['m']))
  $month = intval($_GET['m']);

if (!empty($_GET['y']))
  $year = intval($_GET['y']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>MyVibes - Artists and Tweets update</title>
</head>

<body>
<?php
$tl = new TweetList();

// Get Artists from DB
$artistList = new ArtistList();
$al = $artistList->getArtistList();
foreach ($al as $artist) {
  $screen_name = $artist->getScreenName();
  echo 'Processing ' . $screen_name . '<br />'; 
  // Update missing Artist info
  $ida = $artist->getId();
  $artist->loadArtistFromTwitter($screen_name);
  
  if (!$ida)
    $artist->saveToDb(false);
  else  
    $artist->saveToDb();    

  // Fetch Artist Tweets from TwitterAPI
  $tweet = new Tweet();
  $since_id = $tweet->maxTweetId($artist->getId());
  $tl->getTweetsFromTwitter($screen_name, $month, $year, $since_id);
  $tl->saveToDb();
}
echo 'Done';
?>
</body>
</html>