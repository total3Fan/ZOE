<?php
/*##########################################################################
DO NOT CHANGE THE BELOW THIS FILE COLLECTS THE TRADES FROM TRADINGVIEW.
© 2023 Zoe is licensed under Attribution-ShareAlike 4.0 International 
##########################################################################*/

sleep(1);


include('tv_dbConfig.php');
include('tv_binanceFunctions.php');
include('tv_budgets.php');



// Read the data sent via POST
$inputData = file_get_contents('php://input');
$data = json_decode($inputData, true);

// Split the input data into an array
$values = explode(',', $inputData);

// Check if there are 14 elements in the array
if (count($values) == 7) {
    $originalString = $values[1];
    $cleanString = str_replace(".P", "", $originalString);

    // Assign values from the array to variables
    $symbol 		= $cleanString;
    $timeline 		= $values[3];
    $price 			= $values[2];
    $trade 			= strtolower($values[0]);

    // Extract fib values from the array
     $priceA = $values[5];
     $priceB = $values[6];
	 
	 if($priceA > $priceB)
	 {
		 $priceTop 		= $priceA;
		 $priceBottom 	= $priceB;
	 }else
	 {
		 $priceTop 		= $priceB;
		 $priceBottom 	= $priceA;
	 }
	 $getTickSize = getTickSize($symbol);
	 if($trade == 'short')
	 {
		 
		 
		  $stopPrice = number_format(($price * $stopLossShortFigure), $getTickSize, '.', '');
			$targetPrice = number_format(($price * $targetPointShortFigure), $getTickSize, '.', '');
		 
	 }
	 elseif($trade == 'long')
	 {
		  $stopPrice = number_format(($currentPrice * $stopLossLongFigure), $getTickSize, '.', '');
		  $targetPrice = number_format(($currentPrice * $targetPointLongFigure), $getTickSize, '.', '');
	 }
	
	 
	 

    // Trim whitespace from variables (not modifying the original variables)
    $symbol 		= trim($symbol);
    $trade 			= trim($trade);
    $timeline 		= trim($timeline);

    // Include necessary files



   

    // Database connection code (assuming $config object is defined somewhere)
    $conn = mysqli_connect($hostname, $username, $password, $database);

if($timeline > 10)
{
// Prepare a DELETE statement with placeholders
$updateQuick = "DELETE FROM tradingview_alerts WHERE tradingview_timeline >= ? AND tradingview_symbol = ?";
$stmt = $conn->prepare($updateQuick);

// Bind the parameters to the statement
$stmt->bind_param("is", $tradeTimelineMin, $symbol);

// Execute the statement
$stmt->execute();

$stopPriceFloat = floatval($stopPrice);
$targetPriceFloat = floatval($targetPrice);
$priceFloat = floatval($price);

// Prepare an INSERT statement with placeholders
$sqlInsertUsage = "INSERT INTO tradingview_alerts (
    trade_info,
    tradingview_symbol,
    tradingview_trade,
    tradingview_timeline,
    tradingview_stop,
    tradingview_target,
    tradingview_price
) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sqlInsertUsage);

// Bind the parameters to the statement
$stmt->bind_param("sssisss", $inputData, $symbol, $trade, $timeline, $stopPriceFloat, $targetPriceFloat, $priceFloat);

// Execute the statement
$stmt->execute();

}
mysqli_close($conn);
}


?>
