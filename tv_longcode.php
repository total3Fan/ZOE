<?php


/*##########################################################################
DO NOT CHANGE THE BELOW THIS FILE LISTS LONG TRADES.
© 2023 Zoe is licensed under Attribution-ShareAlike 4.0 International 
##########################################################################*/
	
	
	
$tradeArray = [];
$ignoreTrade = 0;
$skipArray = 0;

						// Extracting row values

$getTickSize = getTickSize($symbol);
$getLotSize = getLotSize($symbol);



$currentPrice = getCurrentPrice($symbol);


$stopPrice = number_format(($currentPrice * $stopLossLongFigure), $getTickSize, '.', '');
$targetPrice = number_format(($currentPrice * $targetPointLongFigure), $getTickSize, '.', '');






$cancelBuy = $targetPrice;

// Get Position Info
$profitPercentage = 0;
$positionInfo = getPosition($symbol, $user_bnKey, $user_bnSecret);
$currentMinutes = (int) date('i');
$coins = $positionInfo[0]['positionAmt'];
$SHORTcoins = abs($positionInfo[1]['positionAmt']);
$positionSide = $positionInfo[0]['positionSide'];

$price = $positionInfo[0]['entryPrice'];
$profitAmt = $positionInfo[0]['unRealizedProfit'];
$exposureCheck = $coins * $price;

if($killShort == 1 && $SHORTcoins > 0)
{
$makeOrder = closeShortFuturesOrder($symbol, $SHORTcoins, $user_bnKey, $user_bnSecret);
$openOrdersCheck--;
}

if ($positionSide == 'LONG' && $coins == 0 && $openOrdersCheck < $maxPairings) 
	{


	if (isset($existingSymbols[$symbol])) {
		
		
		} else {
		$getOpenOrders = openOrders($symbol, $user_bnKey, $user_bnSecret);

		foreach ($getOpenOrders as $item) 
		{
			if ($item['positionSide'] == 'LONG') {
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
	
	
		$orderResponse = createLongOrder($symbol, $amountCoins, $user_bnKey, $user_bnSecret);
		
		
			$orderID = $orderResponse['orderId'];
		if ($orderID > 0) 
		{
			
			$stopLossResponse = createStopLoss($symbol, $stopPrice, $user_bnKey, $user_bnSecret);
			$takeProfitResponse = createTargetOrder($symbol, $targetPrice, $user_bnKey, $user_bnSecret);
			// add symbol to stop duplicates
			
			$existingSymbols[$symbol] = true;
				
		}
	}
	$openOrdersCheck++;
}

		?>