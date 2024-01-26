<?php

/*#################################################################################
THIS FILE ALLOWS YOU TO CHANGE SETTINGS FOR THE APPLICATION, PLEASE READ COMMENTS
© 2023 Zoe is licensed under Attribution-ShareAlike 4.0 International 
START HERE FOR VARIABLES TO CHANGE OR UPDATE
##########################################################################*/


// WHAT LEVERAGE DO YOU WANT TO USE ON YOUR TRADES?

$leverage 				= 20; 

// WHAT TRADE MINIMUM TIMELINES DO YOU WANT TO ACCEPT AND LIST TRADES FOR 
// ANY TRADE TIMELINE THAT IS BELOW THIS NUMBER WILL NOT BE LISTED
// 60 = 1HOUR

$tradeTimelineMin		= 5;

// HOW LONG DO YOU WANT TRADES TO SIT IN THE DATABASE FOR ONCE LISTED, AS THEY WILL RETRIGGER IF CLOSED AND WITHIN THIS TIME THRESHOLD
// IF YOU HAVE 30 THEN THEY WILL BE REMOVED AFTER 30 MINUTES
// IF YOU HAVE 1 THEN THEY WILL BE REMOVED AFTER 1 MINUTE

$leaveDatabaseTrades 	= 5;

// MAXIMUM TRADES ALLOWED AT ONE TIME (CHECK YOUR BUDGETS AGAINST LIQUIDATION)

$maxPairings 			= 8;

// WHEN UNREALISED PROFIT IS GREATER THAN THE FOLLOWING IT WILL CLOSE ALL TRADES AND START AGAINST
// 100 = 100% OF BALANCE
// 50 = 50% OF BALANCE
// 10 = 10% OF BALANCE

$cutOffPercent					=  5;


// WHAT BUDGET DO YOU WANT TO SPEND ON EACH TRADE, THIS INCLUDES ANY LEVERAGE YOU HAVE SET 

$spendBudget			= 100;


// OVER-RIDE INITIAL STOP LOSS PERCENTAGE

$overRideSL = 1;
// DEFAULT IS 0.5% : YOU CAN PLACE A NEW PERCENTAGE IF DESIRED (EXCLUDES LEVERAGE)

$stopLossPercent = 5; // 5%







// OVER-RIDE INITIAL TARGET PERCENTAGE

$overRideTarget = 1;
// DEFAULT IS 0.5FIB : YOU CAN PLACE A NEW PERCENTAGE IF DESIRED (EXCLUDES LEVERAGE)

$targetPointPercent = 25; // 10%



//  SET DIFFERENT TARGET % FOR DIFFERENT TIMELINES
// IF YOU WANT TO USE MULTI-TARGET THEN SET THE BELOW TO 1 OR LEAVE TO 0 TO USE OTHER SETTINGS YOU HAVE
// KEEP $overRideTarget = 1 when using this feature

$multiTargets = 1;


// BELOW ARE THE TARGETS FOR LONG TRADES

// FIRST NUMBER IS YOUR TIMELINE
// SECOND NUMBER IS YOUR % TARGET INCLUDING LEVERAGE

$tradeTargetsByTimeline = [

		5 => 10,
		10 => 16,
		15 => 20, 
		30 => 30, 
		60 => 50, 
		120 => 200
		];


// BELOW ARE THE TARGETS FOR SHORT TRADES

// FIRST NUMBER IS YOUR TIMELINE
// SECOND NUMBER IS YOUR % TARGET INCLUDING LEVERAGE
$tradeShortsByTimeline = [

		5 => 10,
		10 => 16,
		15 => 20, 
		30 => 30, 
		60 => 50, 
		120 => 200
		];
	









	
// THESE ARE THE TRAILING STOP LOSS SETTINGS FOR LONG TRADES, CHANGE AS REQUIRED	
// ['min' => 50,	'max' => 100,	'desiredProfit' => 10],

// WHEN % OF PROFIT IS EQUAL OR OVER THE 50% MARK AND BELOW OR EQUAL TO THE 100% 
// ONCE THE FILE tv_positions.php IS TRIGGERED, IT WILL MOVE THE SL TO TAKE 10% PROFIT
// THEN WILL WAIT FOR THE NEXT MIN LEVEL TO MOVE THE SL AGAIN.
	
$profitThresholdsLong =   [
								
								 
								['min' => 15, 'max' => 25, 'desiredProfit' => 2],
								['min' => 25, 'max' => 300, 'desiredProfit' => 5],
								['min' => 300, 'max' => 600, 'desiredProfit' => 150],
								['min' => 600, 'max' => 800, 'desiredProfit' => 500],
								['min' => 800, 'max' => 1040, 'desiredProfit' => 700],
								['min' => 1680, 'max' => 1760, 'desiredProfit' => 1520],
								['min' => 1760, 'max' => 1840, 'desiredProfit' => 1600],
								['min' => 1840, 'max' => 1920, 'desiredProfit' => 1680],
								['min' => 1920, 'max' => 2000, 'desiredProfit' => 1750]
								
								
];


// THESE ARE THE TRAILING STOP LOSS SETTINGS FOR SHORT TRADES, CHANGE AS REQUIRED	
// ['min' => 50,	'max' => 80,	'desiredProfit' => 3],

// WHEN % OF PROFIT IS EQUAL OR OVER THE 50% MARK AND BELOW OR EQUAL TO THE 80% 
// ONCE THE FILE tv_positions.php IS TRIGGERED, IT WILL MOVE THE SL TO TAKE 3% PROFIT
// THEN WILL WAIT FOR THE NEXT MIN LEVEL TO MOVE THE SL AGAIN.


$profitThresholdsShort =   [
									
									
									
								 
								['min' => 15, 'max' => 25, 'desiredProfit' => 2],
								['min' => 25, 'max' => 300, 'desiredProfit' => 5],
								['min' => 300, 'max' => 600, 'desiredProfit' => 150],
								['min' => 600, 'max' => 800, 'desiredProfit' => 500],
								['min' => 800, 'max' => 1040, 'desiredProfit' => 700],
								['min' => 1680, 'max' => 1760, 'desiredProfit' => 1520],
								['min' => 1760, 'max' => 1840, 'desiredProfit' => 1600],
								['min' => 1840, 'max' => 1920, 'desiredProfit' => 1680],
								['min' => 1920, 'max' => 2000, 'desiredProfit' => 1750]
								
								
								   
							
];
/*#######################################################################################
DO NOT TOUCH BELOW
#######################################################################################*/
$futuresBalance = futuresBalances($user_bnKey, $user_bnSecret, 'USDT');


$cutOff 						= ($futuresBalance * ($cutOffPercent / 100));

// For short positions
$stopLossShortFigure = 1 + ($stopLossPercent / 100); // Stop loss for short should be positive
$targetPointShortFigure = 1 - ($targetPointPercent / 100); // Target for short should be negative

// For long positions
$stopLossLongFigure = 1 - ($stopLossPercent / 100); // Stop loss for long should be negative
$targetPointLongFigure = 1 + ($targetPointPercent / 100); // Target for long should be positive



$maxBudgetPerPairing 	= number_format(($spendBudget),0,'.','');

$maxSpendBudget 		= $spendBudget;

$spendBudgetAmt			= ($spendBudget);

$dateToCheck = date('Y-m-d');
// Change this to your desired date
									
// Create a DateTime object for the dateToCheck
$dateTime = new DateTime($dateToCheck);

// Loop through the last 7 days up to the dateToCheck

// equates your budget
$futuresExposure		= (($futuresBalance * $leverage));



?>
