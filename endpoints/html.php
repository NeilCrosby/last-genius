<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Last Genius</title>
    <style>
        body,
        a {
            background: #000;
            color: #eee;
            text-decoration: none;
            font-family: Helvetica, Arial, Sans-serif;
            font-size: 150%;
        }
    
        li,
        p,
        .duration {
            display: none;
        }
        
        li {
            font-size: 50%;
            display: block;
        }
        
        li.current {
            font-size: 100%;
            display: block;
        }
        
        iframe {
/*            width: 100%;
            height: 300px;*/
            position: absolute;
            top: 0;
            left: 0;
            width: 1px;
            height: 1px;
            border: none;
        }
    </style>
</head>
<body>
<?php
    echo '<ol id="playlist">';
    $class = ' class="current"';
    foreach ($playlist as $item) {
        echo "<li$class><a href=\"{$item->url}\">{$item->artist->name} - {$item->name} <span class=\"duration\">{$item->duration}</span></a></li>\n";
        $class = '';
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
            YAHOO.util.Event.onContentReady('music-player1', this.loadNewTrack, 0); 
        },
        
        loadNewTrack: function(trackNum) {
            console.log('time to load a new track');
            var id = ( trackNum / 2 === parseInt(trackNum / 2, 10) ) ? 1 : 2;
            
            var listEl = yud.get('playlist');
            var listEls = listEl.getElementsByTagName('li');
            if (trackNum >= listEls.length) {
                return;
            }
            
            var temp = listEls[trackNum].getElementsByTagName('a');
            var url = temp[0].href;
            
            var player = document.getElementById('music-player'+id);
            player.src = url + '?autostart';
            
            setTimeout( "YAHOO.tct.lastGenius.destroyTrack(" + (trackNum - 1) + ")", 2000);
            
            
            var timeToNewTrack = 30000;
            var elsDuration = yud.getElementsByClassName('duration', 'span', temp[0]);
            if ( elsDuration.length > 0 ) {
                var duration = parseInt(elsDuration[0].innerHTML, 10);
                if ( duration > 0 ) {
                    timeToNewTrack = duration;
                }
            }
            console.log(timeToNewTrack);
            setTimeout( "YAHOO.tct.lastGenius.loadNewTrack(" + (trackNum + 1) + ")", timeToNewTrack);
        },

        destroyTrack: function(trackNum) {
            var id = ( trackNum / 2 === parseInt(trackNum / 2, 10) ) ? 1 : 2;

            var player = document.getElementById('music-player'+id);
            player.src = '';

            var listEl = yud.get('playlist');
            var listEls = listEl.getElementsByTagName('li');
            
            if (trackNum >= 0) {
                listEls[trackNum].className = '';
            }
            listEls[trackNum + 1].className = 'current';
            
        }

        

    };
}();

YAHOO.tct.lastGenius.init();

</script>

</body>
<html>