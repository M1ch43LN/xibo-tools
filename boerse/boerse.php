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
			color: #fff;
			
			text-align: center;
			padding-bottom: 5px;
			padding-top: 5px;
			margin-bottom: 20px;
		}
		
		table td {
			font-family: 'Roboto', sans-serif;
			font-size: 16pt;
		}
		
		table.kurse {
			width: 100%;
			border: none;
			border-collapse: collapse;
			margin-bottom: 10px;
		}

		table.kurse .hideme {
			display: none;
		}
		
		table.kurse td {
			padding: 5px;
			border-top: 1px solid #808080;
			border-bottom: 1px solid #808080;
		}

		table.kurse td.title_small a {
			color: #000;
			text-decoration: none;
		}
		
		table.kurse td.value {
			text-align: right;
		}
		
		table.kurse td.diff {
			text-align: right;
		}

		table.kurse td.diff span.plus {
			color: green;
		}
		
		table.kurse td.diff span.minus {
			color: red;
		}

		#quelle {
			position: absolute;
			width: 100%;
			bottom: 0px;
			font-size: 10pt;
			font-weight: 200;
			text-align: center;
		}
		
		#chart {
			width: 100%;
			text-align: center;
			margin-bottom: 20px;
		}
		
	</style>
</head>
<body>
<h1>Börsendaten</h1>
<div id="chart"><img id="chart" style="border: none;" src="chart.php"></div>

<?php
//Börsensymbole, dessen Werte geladen werden sollen. Hier herauszufinden: http://de.finance.yahoo.com/
$arrSymbole = array(
	"^GDAXI" => "DAX",
	"^MDAXI" => "MDAX",
	"^TECDAX" => "TecDAX",
	"^SDAXI" => "SDAX",
	"^STOXX50E" => "EuroStoxx 50",
	"^GSPC" => "S&P 500",
	"^NDX" => "Nasdaq 100",
	"EURUSD=X" => "1 Euro in $",
	"XAUUSD=X" => "Gold"
	);

$kurseURL  = "http://download.finance.yahoo.com/d/quotes.csv?s=";
$kurseURL .= urlencode(implode(",",array_keys($arrSymbole)));
$kurseURL .= "&f=snl1p2&e=.csv";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $kurseURL);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$strKurse = curl_exec($ch);
curl_close($ch);

/*
echo "<!--\r\n";
echo $kurseURL . "\r\n\r\n";
echo $strKurse;
echo "\r\n-->\r\n";
*/

$arrKurse = explode("\n", $strKurse);

echo "<table class='kurse'>";
for($iSymbol=0;$iSymbol<count($arrKurse)-1;$iSymbol++) {
	$arrKurse[$iSymbol] = str_replace("\"", "", $arrKurse[$iSymbol]);
	$arrKurse[$iSymbol] = str_replace("N/A", "", $arrKurse[$iSymbol]);
	echo "<tr>";
	$arrKurs = explode(",", $arrKurse[$iSymbol]);
	echo "<td class='title_small'>" . $arrSymbole[$arrKurs[0]] . "</td>";	
	echo "<td class='value'>" . $arrKurs[2] . "</td>";
	echo "<td class='diff'><span class='" . (substr($arrKurs[3], 0, 1)=="+"?"plus":(substr($arrKurs[3], 0, 1)=="-"?"minus":"")) . "'>" . $arrKurs[3] . "</span></td>";
	echo "</tr>";
}
echo "</table>";
?>

<p id="quelle"><a href="<?php echo $kurseURL; ?>">Datenquelle: Yahoo Finance API</a></p>
</body>
</html>