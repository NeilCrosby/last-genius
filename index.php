<?php

require_once('config/config.php');
require_once('classes/CurlCall.php');
require_once('classes/PhpCache.php');

$playlist = array();
$numTracksRequired = 10;
$artist = 'Rick Astley';
$track = 'Never Gonna Give You Up';

$start = time();

for ( $i=0; $i < $numTracksRequired; $i++ ) {
    
    $chooseFrom = array();
    
    $url = LAST_FM_API_URL.'?method=track.getsimilar&artist='.urlencode($artist).'&track='.urlencode($track).'&api_key='.LAST_FM_API_KEY;
    //error_log($url);

    $curl = new CurlCall();
    $data = $curl->getFromXmlSource($url);
    //error_log(print_r($data, true));
    
    $bestMatch = $data->similartracks->track[0]->match;

    foreach ( $data->similartracks->track as $item ) {
        $isStreamable = $item->streamable;
        $isFullTrack = 0;
        foreach($item->streamable->attributes() as $key => $value) {
            if ( 'fulltrack' == $key ) {
                $isFullTrack = $value;
            }
        }
        
        if (!$isStreamable || !$isFullTrack) {
            continue;
        }
        
        if (isSongAlreadyInPlaylist($item, $playlist)) {
            continue;
        }
        
        if ( $item->match < 0.85 * $bestMatch ) {
            break;
        }
        
        array_push($chooseFrom, $item);
    }
    
    if ( 0 == sizeof($chooseFrom) ) {
        foreach ( $data->similartracks->track as $item ) {
            if (!isSongAlreadyInPlaylist($item, $playlist)) {
                error_log("pushed from the topish - {$item->artist->name} - {$item->name} - {$item->match}");
                array_push($playlist, $item);
                break;
            }
        }
    } else {
        array_push($playlist, $chooseFrom[array_rand($chooseFrom)]);
    }
    
    $artist = $playlist[sizeof($playlist) -1]->artist->name;
    $track  = $playlist[sizeof($playlist) -1]->name;

}

echo '<ol>';
foreach ($playlist as $item) {
    echo "<li>{$item->artist->name} - {$item->name} - {$item->match}</li>\n";
}
echo '</ol>';

$taken = time() - $start;
echo "<p>Took $taken seconds</p>";

function isSongAlreadyInPlaylist($song, $playlist) {
    $songUrl = (string) $song->url;
    
    foreach ( $playlist as $item ) {
        //error_log("{$song->url} {$item->url}");
        if ($songUrl == (string) $item->url) {
            return true;
        }
    }
    
    return false;
}

?>