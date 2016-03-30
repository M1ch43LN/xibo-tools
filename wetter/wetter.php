<?php 	
	
	$ort_id = "GMXX2275"; //ID finden: http://weather.tuxnet24.de/?action=citycode&lang=de
	$refresh = 120;
	$icon_url = "//l.yimg.com/us.yimg.com/i/us/nws/weather/gr/";

	header('content-type: text/html; charset=utf-8');
	header('refresh: ' . $refresh . ';URL="wetter.php"'); 

	$wetter = wetterdaten($ort_id, $icon_url);
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="cache-control" content="no-cache"> 
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" rel="stylesheet" type="text/css">
	<style type="text/css">
		* {
			padding: 0;
			margin: 0;
		}
		
		html, body {
			border: none;
		}

		body {
			font-family: 'Roboto', sans-serif;
			font-size: 12pt;
			background-color: #ffffff;
		}
		
		h1, h2 {
			font-family: 'Roboto', sans-serif;
			font-size: 16pt;
			font-weight: 400;
			
			width: 100%;
			border: none;
			background-color: #636363;
			color: #ffffff;
			
			text-align: center;
			padding: 5px 0 5px 0;
		}
		
		div.aktuell,
		div.vorhersage {
			border: 1px solid #636363;
			margin: 25px auto;
			text-align: center;
			background: #dedede;
			padding: 20px;
		}

		div.aktuell {
			background: #dedede url(<?php echo $wetter[0]['icon'];?>) no-repeat 20px 10px;			
		}

		div.vorhersage#heute {
			background: #dedede url(<?php echo $wetter[1]['icon'];?>) no-repeat 20px 10px;			
		}

		div.vorhersage#morgen {
			background: #dedede url(<?php echo $wetter[2]['icon'];?>) no-repeat 20px 10px;			
		}

		div.aktuell span,
		div.vorhersage span {
			font-size: 16pt;
			display: block;
			text-align: right;
			color: #333;
		}
		
		div.aktuell span.temp {
			font-size: 64pt;
			font-weight: 400;				
			color: #000;
		}

		div.aktuell span.zustand {
			font-size: 26pt;
			font-weight: 300;	
			margin-top: -20px;
			color: #000;
		}

		div.vorhersage span.temp {
			font-size: 36pt;
			font-weight: 300;		
			margin-top: 10px;				
			color: #000;
		}

		div.vorhersage span.zustand {
			font-size: 18pt;
			font-weight: normal;	
			margin-top: -15px;				
			color: #000;
		}
		
		#quelle {
			position: absolute;
			bottom: 0px;
			width: 100%;
			font-size: 10pt;
			font-weight: 200;
			text-align: center;
			clear: both;
		}
	</style>

</head>
<body>
	<h1>Wetter</h1>
	<div class="aktuell">
			<span class='tag'>Aktuell</span>
			<span class='temp'><?php echo $wetter[0]['temperatur']; ?>&deg;C</span><br/> 
			<span class='zustand'><?php echo $wetter[0]['zustand']; ?></span>
	</div>
	<div class="vorhersage" id="heute">
			<span class='tag'>Heute</span>
			<span class='temp'><?php echo $wetter[1]['tief']; ?>&deg;C / <?php echo $wetter[1]['hoch']; ?>&deg;C</span><br/> 
			<span class='zustand'><?php echo $wetter[1]['zustand']; ?></span>
	</div>
	<div class="vorhersage" id="morgen">
			<span class='tag'>Morgen</span>
			<span class='temp'><?php echo $wetter[2]['tief']; ?>&deg;C / <?php echo $wetter[2]['hoch']; ?>&deg;C</span><br/> 
			<span class='zustand'><?php echo $wetter[2]['zustand']; ?></span>
	</div>
	<div id="quelle">Datenquelle: Yahoo Weather API</div>
</body>
</html>

<?php

function wetterdaten($w,$imgurl) {
	$xmlurl = "http://weather.tuxnet24.de/?id=" . $w . "&unit=c&mode=xml";
	
	// Daten von API laden und in SimpleXML konvertieren
	$xml = file_get_contents($xmlurl);
	$api = simplexml_load_string(utf8_encode($xml));
	$wetter = array();
		
	// Aktuelles Wetter
	$wetter[0]['zustand'] = yahoo_code2text($api->current_code);
	$wetter[0]['icon'] = $imgurl . yahoo_code2icon($api->current_code);
	$wetter[0]['temperatur'] = split(" ",$api->current_temp)[0];

	//Vorhersage für heute und morgen	
	$wetter[1]['zustand'] = yahoo_code2text($api->forecast0_code);
	$wetter[1]['icon'] = $imgurl . yahoo_code2icon($api->forecast0_code);
	$wetter[1]['tief'] = split(" ",$api->forecast0_temp_low)[0]; 
	$wetter[1]['hoch'] = split(" ",$api->forecast0_temp_high)[0];

	$wetter[2]['zustand'] = yahoo_code2text($api->forecast1_code);
	$wetter[2]['icon'] = $imgurl . yahoo_code2icon($api->forecast1_code);
	$wetter[2]['tief'] = split(" ",$api->forecast1_temp_low)[0]; 
	$wetter[2]['hoch'] = split(" ",$api->forecast1_temp_high)[0];

	return $wetter;
}

function yahoo_code2text($code) {
	$text = "";
	switch ($code) {
		case 0 : $text="Sturm"; break; //tornado
		case 1 : $text="Sturm"; break; //tropical storm
		case 2 : $text="Sturm"; break; //hurricane
		case 3 : $text="Schweres Gewitter"; break; //severe thunderstorms
		case 4 : $text="Gewitter"; break; //thunderstorms
		case 5 : $text="Schneeregen"; break; //mixed rain and snow
		case 6 : $text="Regen / Graupel"; break; //mixed rain and sleet
		case 7 : $text="Schnee / Graupel"; break; //mixed snow an sleet
		case 8 : $text="Gefrierender Nieselregen"; break; //freezing drizzle
		case 9 : $text="Nieselregel"; break; //drizzle
		case 10 : $text="Eisregen"; break; //freezing rain
		case 11 : $text="Schauer"; break; //showers
		case 12 : $text="Schauer"; break; //showers
		case 13 : $text="Schneeböen"; break; //snow flurries
		case 14 : $text="Leichter Schneefall"; break; //light snow showers
		case 15 : $text="Schneefall"; break; //blowing snow
		case 16 : $text="Schneefall"; break; //snow
		case 17 : $text="Hagel"; break; //hail
		case 18 : $text="Graupel"; break; //sleet
		case 19 : $text="nebelig"; break; //dust
		case 20 : $text="nebelig"; break; //foggy
		case 21 : $text="Nebel"; break; //haze
		case 22 : $text="nebelig"; break; //smoky
		case 23 : $text="stürmisch"; break; //blustery
		case 24 : $text="windig"; break; //windy
		case 25 : $text="frostig"; break; //cold
		case 26 : $text="bewölkt"; break; //cloudy
		case 27 : $text="meistens bewölkt"; break; //mostly cloudy (night)
		case 28 : $text="meistens bewölkt"; break; //mostly cloudy (day)
		case 29 : $text="teils bewölkt"; break; //partly cloudy (night)
		case 30 : $text="teils bewölkt"; break; //partly cloudy (day)
		case 31 : $text="klar"; break; //clear (night)
		case 32 : $text="sonnig"; break; //sunny
		case 33 : $text="heiter"; break; //fair (night)
		case 34 : $text="heiter"; break; //fair (day)
		case 35 : $text="Regen / Hagel"; break; //mixed rain and hail
		case 36 : $text="Hitze"; break; //hot
		case 37 : $text="vereinzelte Gewitter"; break; //isolated thunderstorms
		case 38 : $text="vereinzelte Gewitter"; break; //scattered thunderstorms
		case 39 : $text="vereinzelte Gewitter"; break; //scattered thunderstorms
		case 40 : $text="vereinzelte Schauer"; break; //scattered showers
		case 41 : $text="starker Schneefall"; break; //heavy snow
		case 42 : $text="vereinzelte Schneeschauer"; break; //scattered snow showers
		case 43 : $text="starker Schneefall"; break; //heavy snow
		case 44 : $text="teils bewölkt"; break; //partly cloudy
		case 45 : $text="Gewitter"; break; //thundershowers
		case 46 : $text="Schneeschauer"; break; //snow showers
		case 47 : $text="Gewitter"; break; //isolated thundershowers
		case 3200 : $text=""; break; //not available
	}
	return $text;
}

function yahoo_code2icon($code) {
	$icon = $code . "d.png";
	return $icon;
}

?>
