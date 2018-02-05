<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db-config.php";
require_once "autoload.php";
use alesw\classes\ArtistList;
use alesw\classes\TweetList;

// Get Artists from DB
$artistList = new ArtistList();
$al = $artistList->getArtistList();
$labels = $likes = array();
// List Artist Tweets
foreach ($al as $artist) {
  $labels[] = $artist->getScreenName();
  $tl = new TweetList(); 
  $likes[] = $tl->sumOfLikes($artist->getId());
}
echo implode(",", $labels) . "\n" . implode(",", $likes);
?>