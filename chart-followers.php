<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db-config.php";
require_once "autoload.php";
use alesw\classes\ArtistList;

$artistList = new ArtistList();
$artists = $artistList->getArtists();
$labels = $values = array();
foreach($artists as $artist) {
  $labels[] = $artist->getScreenName();
  $values[] = $artist->getFollowersCount();
}
echo implode(",", $labels) . "\n" . implode(",", $values);
?>