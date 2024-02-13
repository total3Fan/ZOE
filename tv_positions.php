<?php

/*##########################################################################
DO NOT CHANGE THE BELOW THIS FILE CHECKS CURRENT TRADES TO REPOSITION SL.
© 2023 Zoe is licensed under Attribution-ShareAlike 4.0 International 
##########################################################################*/


include('tv_binanceFunctions.php');
include('tv_dbConfig.php');
include('tv_budgets.php');


sleep(1);
						

if($futuresBalance > 10)
{

			
$orderTextArray = [];
$positions = getOpenPositions($user_bnKey, $user_bnSecret);
$validPositions = array_filter($positions, function($position) {
return ($position['positionSide'] === 'LONG' && $position['positionAmt'] > 0) 
|| ($position['positionSide'] === 'SHORT' && $position['positionAmt'] < 0);
});

$openOrdersCheck = count($validPositions);
$totalSpend = 0;									
$totalProfit = 0;									
	

$cutOffTotal = 0;
$n = 1;

// List the positions
foreach ($positions as $position) {
if ($position['positionAmt'] != 0) { // Only non-zero positions
sleep(0.1);

print_r($position);

$amtSpent 		= number_format((abs($position['positionAmt']) * $position['entryPrice']),2,'.','');
$profitPercent  = calculateProfitPercentage($position['unRealizedProfit'], $position['entryPrice'], $amtSpent, $leverage, $position['markPrice']);
$cutOffTotal += $position['unRealizedProfit'];
$symbol = $position['symbol'];
$positionSide = $position['positionSide'];
$entryPrice = $position['entryPrice'];
$currentPrice = $position['markPrice'];
$getTickSize = getTickSize($symbol);
$getLotSize = getLotSize($symbol);
$coins = abs($position['positionAmt']);
$totalProfit = 0;

// Fetch open orders for this symbol
$orders = openOrders($symbol, $user_bnKey, $user_bnSecret);
$stopPriceLong = 0;
$targetPriceLong = 0;
$stopPrice = 0;
$targetPrice = 0;
$stopPriceShort = 0;
$targetPriceShort = 0;
foreach ($orders as $order) {
if ($order['type'] == 'STOP_MARKET' && $order['positionSide'] == 'SHORT') {
$stopPriceShort = $order['stopPrice'];
} elseif ($order['type'] == 'TAKE_PROFIT_MARKET'  && $order['positionSide'] == 'SHORT') {
$targetPriceShort = $order['stopPrice'];
}elseif ($order['type'] == 'STOP_MARKET' && $order['positionSide'] == 'LONG') {
$stopPriceLong = $order['stopPrice'];
} elseif ($order['type'] == 'TAKE_PROFIT_MARKET'  && $order['positionSide'] == 'LONG') {
$targetPriceLong = $order['stopPrice'];
}

//print_r($order);
}




if($position['positionSide'] == 'SHORT')
{

$firstMinValue = $profitThresholdsShort[0]['min'];	


if($position['unRealizedProfit'] < 0)
{
$profitPercent = -$profitPercent;
}

if($position['unRealizedProfit'] > 0)
{
$profitPercent = abs($profitPercent);
if($profitPercent >= $firstMinValue)
{

foreach ($profitThresholdsShort as $threshold) 
{

if ($profitPercent >= $threshold['min'] && $profitPercent < $threshold['max']) 
{ // Note: The conditions might be opposite for SHORTs
if (isset($threshold['desiredProfit'])) {
$newStopPriceCheck = calculatePriceForDesiredProfitShort($threshold['desiredProfit'], $position['entryPrice'], $amtSpent, $leverage);



	$newStopPrice = number_format($newStopPriceCheck, $getTickSize, '.', '');
	$newTargetPrice = number_format($targetPriceShort, $getTickSize, '.', '');
	if($newStopPrice < 	$stopPriceShort || 	$stopPriceShort == 0)
	{
		// Cancel existing STOP_MARKET orders for SHORTs
		$getOpenOrdersStop = openOrders($symbol, $user_bnKey, $user_bnSecret);
		foreach ($getOpenOrdersStop as $itemStop) 
		{
			if ($itemStop['positionSide'] == 'SHORT') {
				$orderDelID = $itemStop['orderId'];
				$cancelResult = cancelOrderById($symbol, $orderDelID, $user_bnKey, $user_bnSecret);
			
			}
			
		}
		$stopLossResponse = createShortStopLoss($symbol, $newStopPrice, $user_bnKey, $user_bnSecret);
		$takeProfitResponse = createShortTargetOrder($symbol, $newTargetPrice, $user_bnKey, $user_bnSecret);
		$stopPriceShort = $newStopPrice;
		$targetPriceShort = $newTargetPrice;
		
		
	}


} else {

$makeOrder = closeShortFuturesOrder($symbol, $coins, $user_bnKey, $user_bnSecret); // Note: use a function that closes short orders


}
break;
}
}
}
}


													
													
}






if($position['positionSide'] == 'LONG')
{
$firstMinValue = $profitThresholdsLong[0]['min'];	

if($position['unRealizedProfit'] > 0)
{
if($profitPercent >= $firstMinValue)
{

foreach ($profitThresholdsLong as $threshold) 
{
sleep(0.1);
if ($profitPercent >= $threshold['min'] && $profitPercent < $threshold['max']) 
{
if (isset($threshold['desiredProfit'])) 
{
$newStopPriceCheck = calculatePriceForDesiredProfit($threshold['desiredProfit'], $position['entryPrice'], $amtSpent, $leverage);

	$newStopPrice = number_format($newStopPriceCheck, $getTickSize, '.', '');
	$newTargetPrice = number_format(($targetPriceLong), $getTickSize, '.', '');
	


	// You might want a different condition for SHORTs, e.g.:
	if($newStopPrice > 	$stopPriceLong || 	$stopPriceLong == 0)
	{
		// Cancel existing STOP_MARKET orders for SHORTs
		$getOpenOrdersStop = openOrders($symbol, $user_bnKey, $user_bnSecret);
		foreach ($getOpenOrdersStop as $itemStop) 
		{
			if ($itemStop['positionSide'] == 'LONG') {
				$orderDelID = $itemStop['orderId'];
				$cancelResult = cancelOrderById($symbol, $orderDelID, $user_bnKey, $user_bnSecret);
			}
			
		}
		$stopLossResponse = createStopLoss($symbol, $newStopPrice, $user_bnKey, $user_bnSecret);
		$takeProfitResponse = createTargetOrder($symbol, $newTargetPrice, $user_bnKey, $user_bnSecret);
		$stopPriceLong = $newStopPrice;
		$targetPriceLong = $newTargetPrice;
	}


} else {
$makeOrder = closeLongFuturesOrder($symbol, abs($position['positionAmt']), $user_bnKey, $user_bnSecret);
}
break; // Exit the loop once a match is found
}
}
}

}

}	



if($cutOff < $cutOffTotal && $cutOff > 0)

{
foreach ($positions as $position) 
{
$symbol = $position['symbol'];
$positionSide = $position['positionSide'];

$getTickSize = getTickSize($symbol);
$getLotSize = getLotSize($symbol);
$coins = abs($position['positionAmt']);
if($position['positionSide'] == 'SHORT')
{
$makeOrder = closeShortFuturesOrder($symbol, $coins, $user_bnKey, $user_bnSecret);
}
if($position['positionSide'] == 'LONG')
{
$makeOrder = closeLongFuturesOrder($symbol, $coins, $user_bnKey, $user_bnSecret);
}
}
}


flush();

				}
				}
				}
				

	
	?>				
	
