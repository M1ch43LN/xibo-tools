<?php

require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$CONSUMER_KEY = "zJ0AHD1ryoLDQd8PtU8qOiNM5";
$CONSUMER_SECRET = "sawh4MFtzc3hUTY7TapR31AZLDSNhqz6JA5JBtyyZA9d8n8DRz";
$ACCESS_TOKEN = "142996027-JJXL9Nn01DhY1UU0U1sCt0RoJTSoOVasoCW3pyRy";
$ACCESS_SECRET = "wZ1noC469lmEoFvAA3uPMADDKCZRYcyjW5katlnW0Sh7P";

$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $ACCESS_TOKEN, $ACCESS_SECRET);
$content = $connection->get("statuses/user_timeline", 
                                array(
                                    "count" => 20, 
                                    "screen_name" => "UnispitalBasel",
                                    "exclude_replies" => "true"
                                )
                            );

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="cache-control" content="no-cache"> 
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css"> 
	    .twitpic, .qrcode {
	        max-height: 200px;    
	    }
	    
	    .twittab td {
	        border: 1px solid #808080;
	    }
	    
	    .twittab .td-userimage {
	        vertical-align: top;
	    }
	</style>
</head>
<body>
    <table class="twittab">
    
<?php

$i = 12;
$iMax = count($content);

echo $iMax . " Tweet(s).<br/>";

for ($i = 0; $i < $iMax; $i++) {
    
    $strImage = false;
    $strLink = false;
    $arrMedia = false;
    $arrURLs = false;
    
    $strTweet = $content[$i]->text;
    $strAccountUserName = $content[$i]->user->name;
    $strAccountUserScreenname = $content[$i]->user->screen_name;
    $strAccountUserImage = $content[$i]->user->profile_image_url;
    $strTime = date("d.m.Y H:i:s" , strtotime($content[$i]->created_at));
    
    //Wurde der Tweet retweeted?
    if ($content[$i]->retweeted_status) {
        $bolRetweet = true;
        $strRetweetUserName = $content[$i]->retweeted_status->user->name;
        $strRetweetUserScreenname = $content[$i]->retweeted_status->user->screen_name;
        $strRetweetUserImage = $content[$i]->retweeted_status->user->profile_image_url;
        $strTweet = str_replace("RT @" . $strRetweetUserScreenname . ": ", "", $strTweet);
    } else {
        $bolRetweet = false;
    }
    
    $arrMedia = $content[$i]->entities->media;
    $arrURLs = $content[$i]->entities->urls;
    
    if (is_array($arrMedia) && count($arrMedia) > 0) {
        $strImage = $arrMedia[0]->media_url;
        $strTweet = str_replace($arrMedia[0]->url, "", $strTweet);
    }
    
    if (is_array($arrURLs) && count($arrURLs) > 0) {
        $strLink = $arrURLs[0]->url;
        $strTweet = str_replace($strLink, "", $strTweet);
    }
    
    if ($bolRetweet) {
        echo "<tr>";
        echo "<td></td>";
        echo "<td colspan='2'>" . $strAccountUserName . " retweetete</td>";
        echo "</tr>";
    } 
    
    echo "<tr>";
    echo "<td rowspan='2' class='td-userimage'><img src='" . ($bolRetweet ? $strRetweetUserImage : $strAccountUserImage) . "'/></td>";
    echo "<td class='td-user'>" . ($bolRetweet ? $strRetweetUserName . " (@" . $strRetweetUserScreenname . ")" : $strAccountUserName . " (@" . $strAccountUserScreenname . ")") . "</td>";
    echo "<td class='td-time'>" . $strTime . "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class='td-tweet' colspan='2'>" . $strTweet . "</td>";
    echo "</tr>";
    
    if ($strLink || $strImage) {
        echo "<tr>";
        echo "<td></td>";
        if ($strImage) {
            echo "<td" . (($strLink) ? "" : " colspan='2'") . "><img src='" . $strImage . "' class='twitpic'/></td>";    
        }
        if ($strLink) {
            echo "<td" . (($strImage) ? "" : " colspan='2'") . "><img src='https://api.qrserver.com/v1/create-qr-code/?size=400x400&ecc=Q&data=" . urlencode($strLink) . "' class='qrcode'/></td>";    
        }
        echo "</tr>";
    }

    echo "<tr><td colspan='3'><hr/></td></tr>";
    
}

?>

    </table>

<?php

echo "<xmp>";
print_r ($content[1]);
echo "</xmp>";

?>

</body>
</html>