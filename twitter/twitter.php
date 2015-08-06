<?php

require "config.php";
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $ACCESS_TOKEN, $ACCESS_SECRET);
$content = $connection->get("statuses/user_timeline", 
                                array(
                                    "count" => $MAXTWEETS, 
                                    "screen_name" => $TWITTERACCOUNT,
                                    "exclude_replies" => "true"
                                )
                            );

$iTweets = count($content);

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="cache-control" content="no-cache"> 
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" rel="stylesheet" type="text/css">
	<style type="text/css"> 
	
		* {
			padding: 0;
			margin: 0;
			font-family: 'Roboto', sans-serif;
		} 
		
		html, body {
			border: none;
		}

		body {
			font-family: 'Roboto', sans-serif;
			font-size: 10pt;
			background-color: #ffffff;
		}
		
		#container {
		  padding: 10px;
		}
		
		#image, #link {
			background-repeat: no-repeat;
			background-size: contain;
		}
		
		#image {
		  min-height: 300px;
		  display: none;
		  margin-top: 10px;
		}
		
		#link {
		  width: 58px;
		  height: 58px;
		  float: right;
		  margin-top: 5px;
		  margin-left: 10px;
		  margin-bottom: 10px;
		  display: none;
		}
		
		#userinfo {
		  width: 100%;
		  min-height: 64px;
		  margin-top: 10px;
		}
	
		#userinfo #twitter {
		  width: 32px;
		  height: 32px;
		  float: right;
		  margin-left: 10px;
		  border-radius: 5px;
		  background: url('https://g.twimg.com/dev/documentation/image/Twitter_logo_blue_16.png') no-repeat center center;
		}
	
		#userinfo #userimage,
		#quoteinfo #quoteimage {
		  width: 48px;
		  height: 48px;
		  float: left;
		  margin-right: 10px;
		  border-radius: 5px;
		}
		
		#userinfo #userimage {
			border: 1px solid #ffffff;
		}
		
		#quoteinfo #quoteimage {
			border: 1px solid #f0f0f0;
		}
	
		#userinfo #username,
		#quoteinfo #quotename {
			font-weight: 400;
			font-size: 1.2em;
		}
		
		#userinfo #userscreenname,
		#quoteinfo #quotescreenname,
		#tweettime {
			font-weight: 300;
			color: #808080;
			font-size: 0.9em;
			display: inline;
		}
		
		#userinfo #retweet {
			margin-right: 5px;
			display: none;
		}
		
		#userinfo #userscreenname {
			margin-right: 5px;
		}
		
		#tweettime {
			margin-top: 10px;
			margin-bottom: 5px;
			display: block;
		}
		
		#tweet {
			font-weight: 200;
			font-size: 1.4em;
		}
		
		.hashtag, .link {
			color: <?php echo $HASHTAGCOLOR; ?>;
		}
		
		.link {
			font-weight: 300;
		}
		
		#quotecontainer {
			min-height: 50px;
			clear: both;
			display: none;
			border: 1px solid <?php echo $HASHTAGCOLOR; ?>;
			background-color: #f0f0f0;
			border-radius: 5px;
			padding: 10px;
			margin-top: 20px;
		}
		
		#quote {
			font-size: 1.2em;
			font-weight: 200;
			color: #404040;
			clear: both;
			margin-left: 58px;
		}
	    
	</style>
	<script type="text/javascript"> 
	<!--
		var arrTweets = new Array(<?php echo $iTweets; ?>);
		
		<?php
			for ($i = 0; $i < $iTweets; $i++) {
				$strImage = false;
			    $strLink = false;
			    $arrMedia = false;
			    $arrURLs = false;
			    
			    $strTweet = $content[$i]->text;
			    
			    $strAccountUserName = $content[$i]->user->name;
			    $strAccountUserScreenname = $content[$i]->user->screen_name;
			    $strAccountUserImage = $content[$i]->user->profile_image_url;
			    $strTime = date($DATUMSFORMAT , strtotime($content[$i]->created_at));
			    
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
			    
			    //Wurde ein Tweet zitiert?
			    if ($content[$i]->quoted_status) {
			    	$bolQuote = true;
			    	$strQuoteUserName = $content[$i]->quoted_status->user->name;
			        $strQuoteUserScreenname = $content[$i]->quoted_status->user->screen_name;
			        $strQuoteUserImage = $content[$i]->quoted_status->user->profile_image_url;
			        $strQuote = $content[$i]->quoted_status->text;
			    } else {
			    	$bolQuote = false;
			    }
			    
			    $arrMedia = $content[$i]->entities->media;
			    $arrURLs = $content[$i]->entities->urls;
			    
			    if (is_array($arrMedia) && count($arrMedia) > 0) {
			        $strImage = $arrMedia[0]->media_url;
			        $strTweet = str_replace($arrMedia[0]->url, "", $strTweet);
			    }
			    
			    if (is_array($arrURLs) && count($arrURLs) > 0) {
			    	if ($content[$i]->is_quote_status != 1) {
			        	$strLink = $arrURLs[0]->url;
			    	}
			        $strTweet = str_replace($arrURLs[0]->url, '<span class="link">' . $arrURLs[0]->display_url . '</span>', $strTweet);
			    }
			    
			    //weitere Ersetzungen im Tweet...
			    $strTweet = str_replace("\n", "<br/>", $strTweet);
			    $strTweet = str_replace("'", "&apos;", $strTweet);
			    $strTweet = preg_replace("/#([A-Za-z0-9_äüöÄÜÖ]*)/", '<span class="hashtag">#$1</span>', $strTweet);
			    $strTweet = preg_replace("/@([A-Za-z0-9_äüöÄÜÖ]*)/", '<span class="hashtag">@$1</span>', $strTweet);
			    $strQuote = str_replace("\n", "<br/>", $strQuote);
			    $strQuote = str_replace("'", "&apos;", $strQuote);
			    $strQuote = preg_replace("/#([A-Za-z0-9_äüöÄÜÖ]*)/", '<span class="hashtag">#$1</span>', $strQuote);
			    $strQuote = preg_replace("/@([A-Za-z0-9_äüöÄÜÖ]*)/", '<span class="hashtag">@$1</span>', $strQuote);
			    
			    echo "arrTweets[" . $i . "] = {\r\n\t\t\t";
			    echo "tweet: '" . $strTweet . "',\r\n\t\t\t";
			    echo "accountName: '" . $strAccountUserName . "',\r\n\t\t\t";
			    echo "accountScreenname: '" . $strAccountUserScreenname  . "',\r\n\t\t\t";
			    echo "accountImage: '" . $strAccountUserImage  . "',\r\n\t\t\t";
			    echo "time: '" . $strTime  . "',\r\n\t\t\t";
			    echo "retweeted: " . ($bolRetweet ? "true" : "false") . ",\r\n\t\t\t";
			    if ($bolRetweet) {
			    	echo "retweetName: '" . $strRetweetUserName . "',\r\n\t\t\t";
			    	echo "retweetScreenname: '" . $strRetweetUserScreenname  . "',\r\n\t\t\t";
			    	echo "retweetImage: '" . $strRetweetUserImage  . "',\r\n\t\t\t";
			    } 
			    echo "quoted: " . ($bolQuote ? "true" : "false") . ",\r\n\t\t\t";
			    if ($bolQuote) {
			    	echo "quoteName: '" . $strQuoteUserName . "',\r\n\t\t\t";
			    	echo "quoteScreenname: '" . $strQuoteUserScreenname  . "',\r\n\t\t\t";
			    	echo "quoteImage: '" . $strQuoteUserImage  . "',\r\n\t\t\t";
			    	echo "quote: '" . $strQuote . "',\r\n\t\t\t";
			    }
			    
			    if ($strImage) {
			    	echo "image: '" . $strImage . "',\r\n\t\t\t";
			    }
			    if ($strLink) {
			    	echo "link: 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&ecc=Q&data=" . urlencode($strLink) . "'\r\n\t\t\t";
			    }
			    echo "};\r\n\r\n\t\t";
			}
		?>
		
		var currentTweet = <?php echo $STARTTWEET; ?>;
		var maxTweet = <?php echo $iTweets - 1;?>;
		
		function nextTweet() {			
			if (currentTweet > maxTweet) {
				currentTweet = 0;
			} else {
				showTweet(currentTweet);
			}
			currentTweet++;			
			window.setTimeout("nextTweet()", (<?php echo $SEKUNDEN;?>*1000));
		}
		
		function showTweet(item) {
			var oTweet = document.getElementById("tweet");
			var oTweetTime = document.getElementById("tweettime");
			var oUserName = document.getElementById("username");
			var oUserScreenname = document.getElementById("userscreenname");
			var oUserImage = document.getElementById("userimage");
			var oImage = document.getElementById("image");
			var oLink = document.getElementById("link");
			var oQuoteName = document.getElementById("quotename");
			var oQuoteScreenname = document.getElementById("quotescreenname");
			var oQuoteImage = document.getElementById("quoteimage");
			var oQuote = document.getElementById("quote");
			var oQuoteContainer = document.getElementById("quotecontainer");
			var oRetweet = document.getElementById("retweet");
			
			oTweet.innerHTML = arrTweets[item].tweet;
			oTweetTime.innerHTML = arrTweets[item].time;
			oUserName.innerHTML = arrTweets[item].accountName;
			oUserScreenname.innerHTML = "@" + arrTweets[item].accountScreenname;
			oUserImage.style.background = "url('" + arrTweets[item].accountImage + "') no-repeat top center";
			
			if (arrTweets[item].retweeted) {
				oUserName.innerHTML = arrTweets[item].retweetName;
				oUserScreenname.innerHTML = "@" + arrTweets[item].retweetScreenname;
				oUserImage.style.background = "url('" + arrTweets[item].retweetImage + "') no-repeat top center";
				oRetweet.style.display = "inline";
			} else {
				oRetweet.style.display = "none";
			}
			
			if (arrTweets[item].image) {
				oImage.style.backgroundImage = "url('" + arrTweets[item].image + "')";
				oImage.style.display = "block";
			} else {
				oImage.style.display = "none";
			}
			
			if (arrTweets[item].link) {
				oLink.style.backgroundImage = "url('" + arrTweets[item].link + "')";
				oLink.style.display = "block";
			} else {
				oLink.style.display = "none";
			}
			
			if (arrTweets[item].quoted) {
				oQuote.innerHTML = arrTweets[item].quote;
				oQuoteName.innerHTML = arrTweets[item].quoteName;
				oQuoteScreenname.innerHTML = "@" + arrTweets[item].quoteScreenname;
				oQuoteImage.style.background = "url('" + arrTweets[item].quoteImage + "') no-repeat top center";
				oQuoteContainer.style.display = "block";
			} else {
				oQuoteContainer.style.display = "none";
			}
			
		}
					
	</script>
</head>
<body onload="nextTweet();">
<div id="container">
  <div id="userinfo">
  	<div id="twitter"></div>
    <div id="userimage"></div>
    <div id="username"></div>
    <img id="retweet" src="https://g.twimg.com/dev/documentation/image/retweet_on.png"/><div id="userscreenname"></div>
  </div>
  <div id="link"></div>
  <div id="tweet"></div>
  <div id="tweettime"></div>
  <div id="quotecontainer">
	  <div id="quoteinfo">
	    <div id="quoteimage"></div>
	    <div id="quotename"></div>
	    <div id="quotescreenname"></div>
	  </div>
	  <div id="quote"></div>
  </div>
  <div id="image"></div>
</div>

<?php //echo "<xmp>"; print_r($content[$STARTTWEET]); echo "</xmp>"; ?>

</body>
</html>