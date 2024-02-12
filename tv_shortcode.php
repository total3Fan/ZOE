<?php

/*##########################################################################
DO NOT CHANGE THE BELOW THIS FILE LISTS SHORT TRADES.
© 2023 Zoe is licensed under Attribution-ShareAlike 4.0 International 
##########################################################################*/



$tradeArray = [];
$ignoreTrade = 0;
$skipArray = 0;

$getTickSize = getTickSize($symbol);
$getLotSize = getLotSize($symbol);

$currentPrice = getCurrentPrice($symbol);



$getStopLossSQL = "SELECT tradingview_target, tradingview_stop FROM tradingview_alerts WHERE tradingview_symbol = '$symbol'";
$resultA = $conn->query($getStopLossSQL);
if ($resultA) {
    while ($getQuickDat = $resultA->fetch_assoc()) {
        $fibSupport  = $getQuickDat['tradingview_stop'];
        $fibTarget  = $getQuickDat['tradingview_target'];
    }
    $resultA->free(); // Free the result set
} else {
    // Handle the case where the query fails
   
}
															


$stopPrice = number_format($fibSupport, $getTickSize, '.', '');
$targetPrice = number_format($fibTarget, $getTickSize, '.', '');

$cancelBuy = $targetPrice;
$checkShortCoins = $balCheckShort[$symbol];

// Get Position Info
$profitPercentage = 0;
$positionInfo = getPosition($symbol, $user_bnKey, $user_bnSecret);
$currentMinutes = (int) date('i');
$positionSide = $positionInfo[1]['positionSide'];
$coins = abs($positionInfo[1]['positionAmt']);
$LONGcoins = $positionInfo[0]['positionAmt'];
$price = $positionInfo[1]['entryPrice'];
$profitAmt = $positionInfo[1]['unRealizedProfit'];
$exposureCheck = $coins * $price;


if($hedgeTrades == 1 && $LONGcoins > 0)
{
	$openOrdersCheck--;
}
elseif($killLong == 1 && $LONGcoins > 0)
{
	$makeOrder = closeLongFuturesOrder($symbol,$LONGcoins, $user_bnKey, $user_bnSecret);
	$openOrdersCheck--;
}
if ($positionSide == 'SHORT' && $coins == 0 && $openOrdersCheck < $maxPairings  && $checkShortCoins == 0) 
{



if (isset($existingSymbols[$symbol])) {
	
	
	} else {
		
	$getOpenOrders = openOrders($symbol, $user_bnKey, $user_bnSecret);

	foreach ($getOpenOrders as $item) 
	{
		if ($item['positionSide'] == 'SHORT') {
			$orderDelID = $item['orderId'];
			
			$cancelResult = cancelOrderById($symbol, $orderDelID, $user_bnKey, $user_bnSecret);
		}
	}	
			
	$setLeverage = setLeverage($symbol, $leverage, $user_bnKey, $user_bnSecret);


	// Handle leverage setting issues
	if (isset($setLeverage['code']) && $setLeverage['code'] < 0) 
	{
		$setLeverage = setLeverage($symbol, 8, $user_bnKey, $user_bnSecret);
	}

	$amountCoins = number_format(($spendBudgetAmt / $currentPrice), $getLotSize, '.', '');

$orderResponse = createShortOrder($symbol, $amountCoins, $user_bnKey, $user_bnSecret);
	
	$orderID = $orderResponse['orderId'];
	if ($orderID > 0) 
	{
		$stopLossResponse = createShortStopLoss($symbol, $stopPrice, $user_bnKey, $user_bnSecret);
		$takeProfitResponse = createShortTargetOrder($symbol, $targetPrice, $user_bnKey, $user_bnSecret);
		
		// add symbol to stop duplicates
		
		$existingSymbols[$symbol] = true;
		
		
		
	}
}
$openOrdersCheck++;

}
?>
