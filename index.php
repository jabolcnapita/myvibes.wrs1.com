<?php
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


if (!empty($_GET['m']))
  $month = intval($_GET['m']);

if (!empty($_GET['y']))
  $year = intval($_GET['y']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>MyVibes</title>
<!-- Load c3.css -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.18/c3.css" rel="stylesheet">
</head>

<body>
<h1>Artist Tweet Info</h1>
<?php
// Get Artists from DB
$artistList = new ArtistList();
$al = $artistList->getArtistList();
foreach ($al as $artist) {
  $posted_tweets = number_format($artist->getStatusesCount(), 0, ',', '.');
  $followers = number_format($artist->getFollowersCount(), 0, ',', '.');
  echo '* Artist <strong>' . $artist->getScreenName() . '</strong> posted <strong>' . $posted_tweets . '</strong> tweets in total and has <strong>' . $followers . '</strong> followers.<br />';
}

// List Artist Tweets
foreach ($al as $artist) {
  $tl = new TweetList(); 
  $tweets = $tl->getTweetsFromDb($artist->getId());
  echo '<h2>' . $artist->getScreenName() . '</h2>';
  echo '<div style="font-size:14px;">';
  foreach ($tweets as $tweet) {
    $retweets = number_format($tweet->getRetweetCount(), 0, ',', '.');
    $likes = number_format($tweet->getFovoriteCount(), 0, ',', '.');
      // 2018-01-01 06:08:00
    $engDate = $tweet->getCreatedAt();
    $sloDate = date('d.m.Y H:i', strtotime($engDate));
    echo '<strong>[Posted]:</strong> ' . $sloDate . ' <strong>[Text]:</strong> ' . $tweet->getText() . ' <strong>[Retweets]:</strong> ' . $retweets . ' <strong>[Likes]:</strong> ' . $likes .  '<br />';    
  }  
  echo '</div>';
}

echo '<br />';
?>

<h2>Artist Followers</h2>
<div id="chart"></div>
<h2>Artist Tweet likes</h2>
<div id="chart2"></div>

<!-- Load the javascript libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.18/c3.min.js"></script>

<!-- Initialize and draw the chart -->
<script src="js/charts.js"></script>

</body>
</html>