<?php

require_once('config/config.php');
require_once('classes/CurlCall.php');
require_once('classes/PhpCache.php');

$artist = 'Rick Astley';
$track = 'Never Gonna Give You Up';

$url = LAST_FM_API_URL.'?method=track.getsimilar&artist='.urlencode($artist).'&track='.urlencode($track).'&api_key='.LAST_FM_API_KEY;
$curl = new CurlCall();
$result = $curl->getFromXmlSource($url);

echo '<pre>';
print_r($result);
echo '</pre>';


?>