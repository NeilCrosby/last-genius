<?php

require_once('config/config.php');
require_once('classes/LastGenius.php');
require_once('classes/CurlCall.php');
require_once('classes/PhpCache.php');

$playlist = array();
$numTracksRequired = 10;
$artist = 'Rick Astley';
$track = 'Never Gonna Give You Up';

if ( isset($_GET['url']) ) {
    $origUrl = $_GET['url'];
    
    if ( 0 === strpos($origUrl, 'http://www.last.fm/music/') || preg_match('/http:\/\/www\.lastfm\.[a-z]+\/music\//', $origUrl) ) {
        $pieces = explode('/', $origUrl);
        $artist = urldecode($pieces[4]);
        $track  = urldecode($pieces[6]);
    }
}

$start = time();

$lastGenius = new LastGenius();
$playlist = $lastGenius->getPlaylist($track, $artist, $numTracksRequired);

$taken = time() - $start;

require_once('endpoints/html.php');

?>