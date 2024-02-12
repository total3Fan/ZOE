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

    $ivalues = [$values[4], $values[5], $values[6]];

	sort($ivalues);
	$priceA = $ivalues[0];
	$priceB = $ivalues[2];
	$targetST = $ivalues[1];
	
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
	 
	 
	// For short positions
	
	// For long positions
	

	 if($trade == 'short')
	 {
		 $stopLossShortFigure 	= 1 + (($stopLossPercent / $leverage)  / 100); // Stop loss for short should be positive
				
		 if($multiTargets == 1)
			{
				 $targetPointPercent  		=   $tradeShortsByTimeline[$timeline];
				 $targetPointShortFigure 	= 1 - (($targetPointPercent / $leverage) / 100); // Target for short should be negative
				 $targetPrice 				= number_format(($price * $targetPointShortFigure), $getTickSize, '.', '');
			 }
			 elseif($overRideTarget == 1)
			{
				 $targetPointShortFigure = 1 - (($targetPointPercent / $leverage) / 100); // Target for short should be negative
				 $targetPrice = number_format(($price * $targetPointShortFigure), $getTickSize, '.', '');
			}
			else
			{
				$targetPrice 			= $priceTop;
			}
		
		
		if($overRideSL == 1)
		{
			if($stopLossFIBS == 1)
			{
				$shortSLprice = ($priceTop * $stopLossShortFigure);
			}
			else
			{
				$shortSLprice = ($price * $stopLossShortFigure);
			}
			$stopPrice = number_format(($shortSLprice), $getTickSize, '.', '');
		}
		else
		{
			if($stopLossFIBS == 1)
			{
				$shortSLprice = ($priceTop * 0.5);
			}
			else
			{
				$shortSLprice = ($price * 0.5);
			}
			$stopPrice = number_format(($shortSLprice), $getTickSize, '.', '');
		}
		
	 }
	 elseif($trade == 'long')
	 {
		 
		$stopLossLongFigure 	= 1 - (($stopLossPercent / $leverage) / 100); // Stop loss for long should be negative
		if($multiTargets == 1)
		 {
			 $targetPointPercent  	=   $tradeTargetsByTimeline[$timeline];
			 $targetPointLongFigure = 1 + (($targetPointPercent / $leverage) / 100); // Target for long should be positive
			 $targetPrice 			= number_format(($price * $targetPointLongFigure), $getTickSize, '.', '');
	
		 }
		 elseif($overRideTarget == 1)
		{
			$targetPointLongFigure 	= 1 + (($targetPointPercent / $leverage) / 100); // Target for long should be positive
			$targetPrice = number_format(($price * $targetPointLongFigure), $getTickSize, '.', '');
		}
		else
		{
			$targetPrice 			= $priceBottom;
		}
		
		if($overRideSL == 1)
		{
			if($stopLossFIBS == 1)
			{
				$longSLprice = ($priceBottom * $stopLossLongFigure);
			}
			else
			{
				$longSLprice = ($price * $stopLossLongFigure);
			}
			$stopPrice = number_format(($longSLprice), $getTickSize, '.', '');
		}
		else
		{
			if($stopLossFIBS == 1)
			{
				$longSLprice = ($priceBottom * 0.5);
			}
			else
			{
				$longSLprice = ($price * 0.5);
			}
			$stopPrice = number_format(($longSLprice), $getTickSize, '.', '');
		}
		
	 }
	
	 
	 

    // Trim whitespace from variables (not modifying the original variables)
    $symbol 		= trim($symbol);
    $trade 			= trim($trade);
    $timeline 		= trim($timeline);

    // Include necessary files



   

    // Database connection code (assuming $config object is defined somewhere)
    $conn = mysqli_connect($hostname, $username, $password, $database);

if($timeline >= $tradeTimelineMin)
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
// Execute the statement and check for errors
if ($stmt->execute() === false) {
    die("Error executing the statement: " . $stmt->error);
} else {
    echo $compound. " Record inserted successfully";
}
}
mysqli_close($conn);
}


?>
