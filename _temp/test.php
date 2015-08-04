<?php

//http://blog.jacobemerick.com/web-development/working-with-twitters-api-via-php-oauth/

//Konfiguration
$CONSUMER_KEY = "zJ0AHD1ryoLDQd8PtU8qOiNM5";
$CONSUMER_SECRET = "sawh4MFtzc3hUTY7TapR31AZLDSNhqz6JA5JBtyyZA9d8n8DRz";
$ACCESS_TOKEN = "142996027-JJXL9Nn01DhY1UU0U1sCt0RoJTSoOVasoCW3pyRy";
$ACCESS_SECRET = "wZ1noC469lmEoFvAA3uPMADDKCZRYcyjW5katlnW0Sh7P";

$iCount = 2;

$strTimeline = getUserTimeline($CONSUMER_KEY, $CONSUMER_SECRET, $ACCESS_TOKEN, $ACCESS_SECRET, $iCount);

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="cache-control" content="no-cache"> 
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<script type="text/javascript"> 
	<!--
		<?php
			$i = 0;
			foreach ($arrItems as $item) {
				
				$i++;
			}		
		?>

		var currentItem = 0;
		var maxItem = <?php echo $iCount - 1;?>;
		
		function nextItem() {			
			if (currentItem > maxItem) {
				currentItem = 0;
			} else {
				showItem(currentItem);
			}
			currentItem++;			
			window.setTimeout("nextItem()", 15000);
		}
		
		function showItem(item) {
						
		}
		
	-->
	</script>
	<link href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" rel="stylesheet" type="text/css">
	<style type="text/css"> 
	    /*
		* {
			padding: 0;
			margin: 0;
		}
	
		html, body {
			border: none;
		}
		
		body{
			font-family: 'Roboto', sans-serif;
			font-size: 12pt;
		}
		
		h1 {
			font-family: 'Roboto', sans-serif;
			font-size: 16pt;
			font-weight: 400;
			
			width: 100%;
			border: none;
			background-color: #636363;
			color: #FFFFFF;
			
			text-align: center;
			padding-bottom: 5px;
			padding-top: 5px;
			margin-bottom: 20px;
		}
		*/
		
		
	</style>
</head>
<body onLoad="nextItem();">
	<h1>Twitter</h1>
	<?php echo $strTimeline; ?>
</body>
</html>




<?php

function getUserTimeline($CONSUMER_KEY, $CONSUMER_SECRET, $ACCESS_TOKEN, $ACCESS_SECRET, $MAXTWEETS) {
    // $CONSUMER_KEY = "zJ0AHD1ryoLDQd8PtU8qOiNM5";
    // $CONSUMER_SECRET = "sawh4MFtzc3hUTY7TapR31AZLDSNhqz6JA5JBtyyZA9d8n8DRz";
    // $ACCESS_TOKEN = "142996027-JJXL9Nn01DhY1UU0U1sCt0RoJTSoOVasoCW3pyRy";
    // $ACCESS_SECRET = "wZ1noC469lmEoFvAA3uPMADDKCZRYcyjW5katlnW0Sh7P";
    
    // TWITTER:
    // GET&https://api.twitter.com/1.1/statuses/user_timeline.json
    // &count=20
    // &oauth_consumer_key=zJ0AHD1ryoLDQd8PtU8qOiNM5
    // &oauth_nonce=6e43773b2ccaf66477ff28bc16881715
    // &oauth_signature_method=HMAC-SHA1
    // &oauth_timestamp=1438502771
    // &oauth_token=142996027-JJXL9Nn01DhY1UU0U1sCt0RoJTSoOVasoCW3pyRy
    // &oauth_version=1.0
    // &screen_name=UniSpitalBasel
    
    // SKRIPT:
    // GET&https://api.twitter.com/1.1/statuses/user_timeline.json
    // &count=2
    // &oauth_consumer_key=zJ0AHD1ryoLDQd8PtU8qOiNM5
    // &oauth_nonce=1438504139
    // &oauth_signature_method=HMAC-SHA1
    // &oauth_timestamp=1438504139
    // &oauth_token=142996027-JJXL9Nn01DhY1UU0U1sCt0RoJTSoOVasoCW3pyRy
    // &oauth_version=1.0


    $oauth_hash = '';
    $oauth_hash .= 'oauth_consumer_key=' . $CONSUMER_KEY . '&';
    $oauth_hash .= 'oauth_nonce=' . time() . '&';
    $oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
    $oauth_hash .= 'oauth_timestamp=' . time() . '&';
    $oauth_hash .= 'oauth_token=' . $ACCESS_TOKEN . '&';
    $oauth_hash .= 'oauth_version=1.0';
    
    $base = '';
    $base .= 'GET';
    $base .= '&';
    $base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');
    $base .= '&';
    $base .= rawurlencode('count=' . $MAXTWEETS);
    $base .= '&';
    $base .= rawurlencode($oauth_hash);
    
    echo "<h1>Signature base string</h1>";
    echo $base . "<br/><hr/>";
    
    $key = '';
    $key .= rawurlencode($CONSUMER_SECRET);
    $key .= '&';
    $key .= rawurlencode($ACCESS_SECRET);
    
    $signature = base64_encode(hash_hmac('sha1', $base, $key, true));
    $signature = rawurlencode($signature);
    
    $oauth_header  = '';
    $oauth_header .= 'oauth_consumer_key="' . $CONSUMER_KEY . '", ';
    $oauth_header .= 'oauth_nonce="' . time() . '", ';
    $oauth_header .= 'oauth_signature="' . $signature . '", ';
    $oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
    $oauth_header .= 'oauth_timestamp="' . time() . '", ';
    $oauth_header .= 'oauth_token="' . $ACCESS_TOKEN . '", ';
    $oauth_header .= 'oauth_version="1.0", ';
    $curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');
    
    echo "<h1>Signature base string</h1>";
    echo "Authorization: Oauth {$oauth_header}" . "<br/><hr/>";
    
    $curl_request = curl_init();
    curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
    curl_setopt($curl_request, CURLOPT_HEADER, false);
    curl_setopt($curl_request, CURLOPT_URL, 'https://api.twitter.com/1.1/statuses/user_timeline.json?count=' . $MAXTWEETS);
    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
    $json = curl_exec($curl_request);
    curl_close($curl_request);
    
    return $json;
}

?>