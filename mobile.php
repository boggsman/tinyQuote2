<?php
session_start();

if (isset($_GET["reset"])) {
	session_destroy();
	header('Location: '.$_SERVER["PHP_SELF"].'');
	exit;
	}
	
if ( ! isset($_SESSION["stockArray"])) {
	$_SESSION["stockArray"][] = "INTC";
	}
	
if (isset($_GET["newStock"])) {

	if (empty($_GET["newStock"])) {
		header('Location: '.$_SERVER["PHP_SELF"].'');
		exit;
		}
		
		array_push($_SESSION["stockArray"], $_GET["newStock"]);
	}
	
if (isset($_GET["deleteStock"])) {
	$key = array_search($_GET["deleteStock"], $_SESSION['stockArray']);
	unset($_SESSION['stockArray'][$key]);
	}

function getQuote($symbol) 
{
	$symbol  = urlencode( trim( substr(strip_tags($symbol),0,7) ) ); 
	$yahooCSV = "http://finance.yahoo.com/d/quotes.csv?s=$symbol&f=sl1d1t1c1ohgvpnbaejkr&o=t";
	$csv = fopen($yahooCSV,"r");

	if($csv) 
	{
		list($quote['symbol'], $quote['last'], $quote['date'], $quote['timestamp'], $quote['change'], $quote['open'],
		$quote['high'], $quote['low'], $quote['volume'], $quote['previousClose'], $quote['name'], $quote['bid'],
		$quote['ask'], $quote['eps'], $quote['YearLow'], $quote['YearHigh'], $quote['PE']) = fgetcsv($csv, ','); 
  
		fclose($csv);
  
		return $quote; 
	} 
	else 
	{
		return false;
	}
}


?>



<html>
<!--[if IEMobile 7 ]>    <html class="no-js iem7"> <![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Stock Quotes</title>
	    <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="cleartype" content="on">

        <!-- For iOS web apps. Delete if not needed. https://github.com/h5bp/mobile-boilerplate/issues/94 -->
        <!--
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        -->

        <!-- This script prevents links from opening in Mobile Safari. https://gist.github.com/1042026 -->
        <!--
        <script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>
        -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/style.css" />
		<script src="js/vendor/modernizr-2.6.1.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
		<script src="js/jquery.ui.touch-punch.min.js"></script>
		<script>
			$(document).ready(function() {
				var dropped = false;

				$("#sortable").sortable({
			
				});

				$(".droppable").droppable({
					tolerance: 'touch',
					activeClass: 'active',
					hoverClass:'hovered',
					drop:function(event,ui){
						window.location = 'mobile.php?deleteStock=' + ui.draggable.attr("id");
					}
				});
				
				$("#newStock").blur(function() {
					$("#addNew").submit();
				});
				
			});
			
				
		</script>
</head>
<body>


<div class="table1">
<table class="grid">
		<thead>
			<tr>
				<th>STOCK</th>
				<th>NAME</th>
				<th>PRICE</th>
				<th>CHANGE</th>
			</tr>
		</thead>
		<tbody id="sortable">
		<?php
		
		foreach ($_SESSION["stockArray"] as $thisStock) {
		$last = getQuote($thisStock);
		echo '<tr id="'.$thisStock.'">';
		echo '	<td>'.$thisStock.'</td>';
		echo '	<td>'.$last["name"].'</td>';
		echo '	<td>'.$last["last"].'</td>';
		echo '	<td>'.$last["change"].'</td>';
		echo '</tr>';
		}
		
		?>
		</tbody>
		
		<tfoot>
		<tr>
			<td>
				<form name="addNew" id="addNew" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="text" name="newStock" id="newStock" size="3" placeholder="Add" required>
				</form>
			</td>
			<td></td>
			<td></td>
			<td><img src="img/trash.png" alt="Drag To Delete Stock" class="droppable"></td>
		</tr>
		</tfoot>
</table>
<br/>

<script src="js/helper.js"></script>
</body>
</html>