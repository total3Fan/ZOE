<?php
/*##########################################################################
DO NOT CHANGE THE BELOW THIS FILE WILL DO THE PROCESS OF PLACING TRADES.
© 2023 Zoe is licensed under Attribution-ShareAlike 4.0 International 
##########################################################################*/



include('tv_dbConfig.php');
include('tv_binanceFunctions.php');
include('tv_budgets.php');

flush();
	


// Create connection

$conn = mysqli_connect($hostname, $username, $password, $database);



							
							
							
				
				
// Prepare the SELECT statement with a placeholder
$getTradeQuick = "SELECT 
    tradingview_symbol,
    tradingview_price,
    tradingview_target,
    tradingview_stop,
    tradingview_trade
FROM 
    tradingview_alerts 
WHERE 
    tradingview_timeline >= ?
ORDER BY 
    tradingview_trade ASC";

$stmt = $conn->prepare($getTradeQuick);

// Bind the parameter
$stmt->bind_param("s", $tradeTimelineMin);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

$symbolsData = [];
$symbolArray = [];
$stopLossArray = [];

if ($result !== false) {
    while ($getQuickDat = $result->fetch_assoc()) {
        $symbol = $getQuickDat['tradingview_symbol'];
        $predict = strtolower($getQuickDat['tradingview_trade']);

        // Add the prediction to the array without additional conditions
        $symbolArray[$symbol] = $predict;
    }
}

// Close the statement
$stmt->close();
		
							
						
if(count($symbolArray) == 0)
{
	
// Calculate the timestamp for the condition
$intervalAgo = new DateTime();
$intervalAgo->modify("-{$leaveDatabaseTrades} minutes");
$formattedIntervalAgo = $intervalAgo->format('Y-m-d H:i:s');

// Prepare the DELETE statement
$cleanQuick = "DELETE FROM tradingview_alerts WHERE tradingview_updated < ?";
$stmt = $conn->prepare($cleanQuick);

// Bind the parameter
$stmt->bind_param("s", $formattedIntervalAgo);

// Execute the statement
$stmt->execute();

mysqli_close($conn);

die();

}	
													
							
$existingSymbols = array();

$openOrdersCheck = 0;
									
								
		if($futuresBalance > 10)
		{
			
			
			$positions = getOpenPositions($user_bnKey, $user_bnSecret);
			//print_r($positions);
		foreach ($positions as $position) {
		if (abs($position['positionAmt']) > 0) {
			$openOrdersCheck++;
			sleep(0.3);
		}
		}
	
				
			sleep(0.5);

			foreach($symbolArray as $symbol => $tradeSide) 
{
			
			
			// SANITY CHECK QUERY START
			
			
			sleep(0.2);
				
			
			//$spendBudgetAmt = 1000;
			$killLong = 0;	
			$killShort = 0;
		
				
						
			
			if(	$tradeSide == 'short')
			{
				
				$killLong = 1;
				include('tv_shortcode.php');
				
				
			}elseif($tradeSide == 'long')
			{
				$killShort = 1;
				include('tv_longcode.php');
				
			}
			
				}
		
				
	

}
						
						
							
// Prepare the DELETE statement outside the loop
$updateQuick = "DELETE FROM tradingview_alerts WHERE tradingview_timeline >= ? AND tradingview_symbol = ? AND tradingview_updated < NOW() - INTERVAL ? MINUTE";
$stmt = $conn->prepare($updateQuick);

// Iterate over each symbol
foreach($symbolArray as $symbol => $tradeSide) {
    // Bind parameters to the statement for each symbol
    $stmt->bind_param("isi", $tradeTimelineMin, $symbol, $leaveDatabaseTrades);

    // Execute the statement
    $stmt->execute();

    // Optionally, check for errors or affected rows here
}



// Prepare a DELETE statement with a placeholder for the interval
$cleanQuick = "DELETE FROM tradingview_alerts WHERE tradingview_updated < NOW() - INTERVAL ? MINUTE";
$stmt = $conn->prepare($cleanQuick);

// Bind the parameter to the statement
$stmt->bind_param("i", $leaveDatabaseTrades);

// Execute the statement
$stmt->execute();
mysqli_close($conn);




								
							
						
					
?>