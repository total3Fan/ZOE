<?php

/*##########################################################################
DO NOT CHANGE THE BELOW THIS FILE CHECKS THAT OLD TRADES ARE NOT LISTED STILL.
© 2023 Zoe is licensed under Attribution-ShareAlike 4.0 International 
##########################################################################*/




include('tv_dbConfig.php');
include('tv_binanceFunctions.php');
include('tv_budgets.php');



					if ($futuresBalance > 10) 
					{
								
						$n = 0;
										
										//echo $doSQL ;
									$getOpenOrders = openAllOrders($user_bnKey, $user_bnSecret);
	
										foreach ($getOpenOrders as $item) {
											$symbol = $item['symbol'];
											$positionSide = $item['positionSide'];
											$currentorigType = $item['origType'];
											$currentStopPrice = $item['stopPrice'];
											$currentPrice = getCurrentPrice($symbol);
											$getTickSize = getTickSize($symbol);
											$getLotSize = getLotSize($symbol);
											
										
											
												$positionInfo = getPosition($symbol, $user_bnKey, $user_bnSecret);
												$positionAmtL = $positionInfo[0]['positionAmt'];
												$positionAmtS = $positionInfo[1]['positionAmt'];
												if($debug == 1)
												{
												print_r($positionInfo);
												}


												if ($positionAmtL == 0 && $positionSide == 'LONG') {
													
													$orderDelID = $item['orderId'];
													$orderType = $item['side'];

														$cancelResult = cancelOrderById($symbol, $orderDelID, $user_bnKey, $user_bnSecret);

													
														
														if($debug == 1)
															{
																print_r($cancelResult);
															}
													
												} 
												elseif ($positionAmtS == 0  && $positionSide == 'SHORT') {
													
													$orderDelID = $item['orderId'];
													$orderType = $item['side'];

														$cancelResult = cancelOrderById($symbol, $orderDelID, $user_bnKey, $user_bnSecret);

														if($debug == 1)
															{
																print_r($cancelResult);
															}
													
												} 
											
									}
									
									sleep(2);

									$positions = getOpenPositions($user_bnKey, $user_bnSecret);
									if($debug == 1)
															{
																print_r($positions);
															}
									
									foreach ($positions as $position) {
										
										
										 if (abs($position['positionAmt']) > 0) {$n++;
										sleep(0.1);
										$symbol 			= $position['symbol'];
										$positionSide 		= $position['positionSide'];
										$entryPrice 		= $position['entryPrice'];
										
										
										$currentPrice 		= $position['markPrice'];
										$coins				= abs($position['positionAmt']);
										$getTickSize 		= getTickSize($symbol);
										$getLotSize 		= getLotSize($symbol);

									

										$getOpenOrders = openOrders($symbol, $user_bnKey, $user_bnSecret);
										
										if($debug == 1)
															{
																print_r($getOpenOrders);
															}
										$stopPresent = 0;
										$takeProfitPresent = 0;
										
										foreach ($getOpenOrders as $item) {
											sleep(0.1);
											
											if ($item['positionSide'] == $positionSide && $item['type'] == 'STOP_MARKET') {
												$stopPresent = 1;
											}
											if ($item['positionSide'] == $positionSide && $item['type'] == 'TAKE_PROFIT_MARKET') {
												$takeProfitPresent = 1;
											}
										}
										$setLeverage = setLeverage($symbol, $leverage, $user_bnKey, $user_bnSecret);
											if (isset($setLeverage['code']) && $setLeverage['code'] < 0) {
												$setLeverage = setLeverage($symbol, 8, $user_bnKey, $user_bnSecret);
											}
											if($debug == 1)
															{
																print_r($setLeverage);
															}
											
										if ($positionSide == 'LONG') {
											if ($coins == 0) {
												// Cancel order logic for when there's no position amount left
												foreach ($getOpenOrders as $item) {
													
													if ($item['positionSide'] == 'LONG') {
														sleep(0.1);
														$orderDelID = $item['orderId'];
														$cancelResult = cancelOrderById($symbol, $orderDelID, $user_bnKey, $user_bnSecret);
													}
												}
											} else {
												if ($stopPresent == 0) {
													
													

													$stopPrice = number_format(($currentPrice * $stopLossLongFigure), $getTickSize, '.', '');
															
															
													
													
													
													$stopLossResponse = createStopLoss($symbol, $stopPrice, $user_bnKey, $user_bnSecret);
													if (isset($stopLossResponse['code']) && $stopLossResponse['code'] < 0) 
													{
														$makeOrder = closeLongFuturesOrder($symbol, $coins, $user_bnKey, $user_bnSecret);
													}
													if($debug == 1)
															{
																print_r($stopLossResponse);
															}
													
													flush();
												}
												if ($takeProfitPresent == 0) {
													$targetPrice = number_format(($entryPrice * $targetPointLongFigure), $getTickSize, '.', '');
													
													
													
													$createTargetOrder = createTargetOrder($symbol, $targetPrice, $user_bnKey, $user_bnSecret);
													if($debug == 1)
															{
																print_r($createTargetOrder);
															}
													flush();
												}
											}
										
										} elseif ($positionSide == 'SHORT') {
											if ($coins == 0) {
												// Cancel order logic for when there's no position amount left
												foreach ($getOpenOrders as $item) {
													
													if ($item['positionSide'] == 'SHORT') {
														sleep(0.1);
														$orderDelID = $item['orderId'];
														$cancelResult = cancelOrderById($symbol, $orderDelID, $user_bnKey, $user_bnSecret);
													}
												}
											} else {
												if ($stopPresent == 0) {
													$stopPrice = number_format(($currentPrice * $stopLossShortFigure), $getTickSize, '.', '');
													$stopLossResponse = createShortStopLoss($symbol, $stopPrice, $user_bnKey, $user_bnSecret);
													if (isset($stopLossResponse['code']) && $stopLossResponse['code'] < 0) 
													{
														$makeOrder = closeLongFuturesOrder($symbol, $coins, $user_bnKey, $user_bnSecret);
													}
													if($debug == 1)
															{
																print_r($stopLossResponse);
															}
													
													flush();
												}
												if ($takeProfitPresent == 0) {
													$targetPrice = number_format(($entryPrice * $targetPointShortFigure), $getTickSize, '.', '');
													
													$createTargetOrder = createShortTargetOrder($symbol, $targetPrice, $user_bnKey, $user_bnSecret);
													if($debug == 1)
															{
																print_r($createTargetOrder);
															}
													flush();
												}
											}
										}
									
									
									}
									}


									

 
	}
	
	


								
								?>			
											
						
					
