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
    
    if ( 0 === strpos($origUrl, 'http://www.last.fm/music/')  ) {
        $pieces = explode('/', $origUrl);
        $artist = urldecode($pieces[4]);
        $track  = urldecode($pieces[6]);
    }
}

$start = time();

$lastGenius = new LastGenius();
$playlist = $lastGenius->getPlaylist($track, $artist, $numTracksRequired);

$taken = time() - $start;

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Last Genius</title>
    <style>
        body {
        }
    </style>
</head>
<body>
<?php
    echo '<ol id="playlist">';
    foreach ($playlist as $item) {
        echo "<li><a href=\"{$item->url}\">{$item->artist->name} - {$item->name} - <span class=\"duration\">{$item->duration}</span></a></li>\n";
    }
    echo '</ol>';

    echo "<p>Took $taken seconds</p>";
?>

<iframe id="music-player1" src="<?php echo $playlist[0]->url; ?>?autostart"></iframe>
<iframe id="music-player2"></iframe>
<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script>
YAHOO.namespace('tct.lastGenius');

YAHOO.tct.lastGenius = function () {

    var yue = YAHOO.util.Event,
        yud = YAHOO.util.Dom;
        
    //the returned object here will become YAHOO.myProject.myModule:
    return  {

        init: function () {
            console.log('init');
            YAHOO.util.Event.onContentReady('music-player1', this.loadNewTrack, 0); 
        },
        
        loadNewTrack: function(trackNum) {
            var id = ( trackNum / 2 === parseInt(trackNum / 2, 10) ) ? 1 : 2;
            console.log(trackNum + ' ' + id);
            
            var listEl = yud.get('playlist');
            var listEls = listEl.getElementsByTagName('li');
            console.log(listEls.length);
            if (trackNum >= listEls.length) {
                return;
            }
            
            var temp = listEls[trackNum].getElementsByTagName('a');
            var url = temp[0].href;
            console.log(url);
            
            var player = document.getElementById('music-player'+id);
            player.src = url + '?autostart';
            
            setTimeout( "YAHOO.tct.lastGenius.destroyTrack(" + (trackNum - 1) + ")", 2000);
            
            setTimeout( "YAHOO.tct.lastGenius.loadNewTrack(" + (trackNum + 1) + ")", 30000);
        },

        destroyTrack: function(trackNum) {
            console.log('destroying');
            var id = ( trackNum / 2 === parseInt(trackNum / 2, 10) ) ? 1 : 2;

            var player = document.getElementById('music-player'+id);
            player.src = '';
        }

        

    };
}();

YAHOO.tct.lastGenius.init();

</script>

</body>
<html>