<?php

$debug = 1;
/*#################################################################################
THIS FILE ALLOWS YOU TO CHANGE SETTINGS FOR THE APPLICATION, PLEASE READ COMMENTS
© 2023 Zoe is licensed under Attribution-ShareAlike 4.0 International 
START HERE FOR VARIABLES TO CHANGE OR UPDATE
##########################################################################*/


// WHAT LEVERAGE DO YOU WANT TO USE ON YOUR TRADES?

$leverage 				= 20; 

// DO YOU WANT ZOE TO DO HEDGING, WHEN YOU HAVE LONG OPEN YOU WANT IT TO LEAVE OPEN AND ALSO OPEN SHORT?
// 1 = HAVE BOTH OPEN  0 = CLOSE LONG FOR SHORT TO OPEN (VISA VERSA)

$hedgeTrades = 0;


// COMPOUND SETTINGS 1 = On   0 = Off
$compound = 1;

// WHAT % BALANCE TO USE FOR BUDGET PER TRADE DEFAULT IS 2% 

$spendBudgetPercent				= 2;

// WHAT % OF FUTURES BALANCE IS MAXIMUM TO USE FOR ALL TRADES DEFAULT IS 10% 

$maxTradesPercent				= 30;


// If you set compound to 0 then following settings apply, otherwise will be ignored
// SpendBudget is amount in USD including leverage; MaxPairings is max number of trades at one time;
// Will be ignored if COMPOUND setting is 1

$spendBudget			= 10;
$maxPairings 			= 10;




// WHAT TRADE MINIMUM TIMELINES DO YOU WANT TO ACCEPT AND LIST TRADES FOR 
// ANY TRADE TIMELINE THAT IS BELOW THIS NUMBER WILL NOT BE LISTED
// 60 = 1HOUR

$tradeTimelineMin		= 1;

// HOW LONG DO YOU WANT TRADES TO SIT IN THE DATABASE FOR ONCE LISTED, AS THEY WILL RETRIGGER IF CLOSED AND WITHIN THIS TIME THRESHOLD
// IF YOU HAVE 30 THEN THEY WILL BE REMOVED AFTER 30 MINUTES
// IF YOU HAVE 1 THEN THEY WILL BE REMOVED AFTER 1 MINUTE

$leaveDatabaseTrades 	= 2;


// WHEN UNREALISED PROFIT IS GREATER THAN THE FOLLOWING IT WILL CLOSE ALL TRADES AND START AGAINST
// 100 = 100% OF BALANCE
// 50 = 50% OF BALANCE
// 10 = 10% OF BALANCE

$cutOffPercent					=  3;

// STOP LOSS ON FIBS OR PRICE
// 1 = FIBS  0 = PRICE

$stopLossFIBS = 1;

// OVER-RIDE INITIAL STOP LOSS PERCENTAGE

$overRideSL = 1;

// DEFAULT IS 0.5% : YOU CAN PLACE A NEW PERCENTAGE IF DESIRED (EXCLUDES LEVERAGE)
// STOP IS NOT FROM ENTRY PRICE BUT FROM THE 1 FIB AT THE TIME OF ENTRY

$stopLossPercent = 100; // 5%


// OVER-RIDE INITIAL TARGET PERCENTAGE

$overRideTarget = 1;

// DEFAULT IS 0.5FIB : YOU CAN PLACE A NEW PERCENTAGE IF DESIRED (EXCLUDES LEVERAGE)
// TARGET IS FROM ENTRY PRICE 
// ONLY APPLICABLE IF $multiTargets = 0;

$targetPointPercent = 12; // 10%



// SET DIFFERENT TARGET % FOR DIFFERENT TIMELINES
// IF YOU WANT TO USE MULTI-TARGET THEN SET THE BELOW TO 1 OR LEAVE TO 0 TO USE OTHER SETTINGS YOU HAVE
// THIS WILL OVER-RIDE TARGET POINT

$multiTargets = 1;


// BELOW ARE THE TARGETS FOR LONG TRADES

// FIRST NUMBER IS YOUR TIMELINE
// SECOND NUMBER IS YOUR % TARGET INCLUDING LEVERAGE

$tradeTargetsByTimeline = [

		4 => 10,
		5 => 10,
		8 => 120,
		10 => 120,
		11 => 140,
		15 => 200, 
		30 => 300, 
		60 => 500, 
		120 => 1000
		];


// BELOW ARE THE TARGETS FOR SHORT TRADES

// FIRST NUMBER IS YOUR TIMELINE
// SECOND NUMBER IS YOUR % TARGET INCLUDING LEVERAGE
$tradeShortsByTimeline = [

		4 => 10,
		5 => 10,
		8 => 10,
		10 => 10,
		11 => 15,
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
								
								 
								['min' => 8, 'max' => 12, 'desiredProfit' => 5],
								['min' => 12, 'max' => 20, 'desiredProfit' => 10],
								['min' => 20, 'max' => 100, 'desiredProfit' => 15],
								['min' => 100, 'max' => 600, 'desiredProfit' => 150],
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
									
									
									
								 
								 
								['min' => 8, 'max' => 12, 'desiredProfit' => 5],
								['min' => 12, 'max' => 20, 'desiredProfit' => 10],
								['min' => 20, 'max' => 100, 'desiredProfit' => 15],
								['min' => 100, 'max' => 600, 'desiredProfit' => 150],
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

// equates your budget
$futuresExposure		= number_format(($futuresBalance * $leverage),0,'.','');

if($compound == 1)
{

$spendBudgetPercentCalc	= number_format($spendBudgetPercent / 100,5,'.','');

$spendBudget			= number_format($futuresExposure * $spendBudgetPercentCalc,2,'.','');

$maxTradesPercentCalc	= number_format($maxTradesPercent / 100,2,'.','');

$maxPairingsBudget 		= number_format($futuresExposure * $maxTradesPercentCalc,2,'.','');

$maxPairings 			= number_format($maxPairingsBudget / $spendBudget,0,'.','');

}


$cutOff 						= ($futuresBalance * ($cutOffPercent / 100));


// For short positions
$stopLossShortFigure = 1 + (($stopLossPercent / $leverage) / 100); // Stop loss for short should be positive
$targetPointShortFigure = 1 - (($targetPointPercent / $leverage) / 100); // Target for short should be negative

// For long positions
$stopLossLongFigure = 1 - (($stopLossPercent / $leverage) / 100); // Stop loss for long should be negative
$targetPointLongFigure = 1 + (($targetPointPercent / $leverage) / 100); // Target for long should be positive



if($debug == 1)
{
	
	$dateToCheck = date('Y-m-d');
 // Change this to your desired date
										

// Create a DateTime object for the dateToCheck
$dateTime = new DateTime($dateToCheck);

// Loop through the last 7 days up to the dateToCheck


$profitToday = calculateProfitOrLoss($dateTime->format('Y-m-d'), $user_bnKey, $user_bnSecret);
echo '<hr>';
							
	echo '<table border="1" width="100%">';
	echo '<tr>';
	echo '<td>USER</td>';
	echo '<td>Percentage Per Trade:</td>';
	echo '<td>Budget Per Trade:</td>';
	echo '<td>Percentage Total Trades:</td>';
	echo '<td>Total Trades Budget:</td>';
	echo '<td>Maximum Trades:</td>';
	echo '<td>STOP LOSS with LEVERAGE: </td>';
	echo '<td>RISK without LEVERAGE: </td>';
	echo '<td>Cut-Off Amount:</td>';
	echo '<td>Profit</td>';
		echo '</tr>';
	echo '<tr>';
	echo '<td>'.$user_userID.'</td>';
	echo '<td>'.$spendBudgetPercent.'%'.'</td>';
		echo '<td>$'.$spendBudget.'</td>';
		echo '<td>'.$maxTradesPercent.'%</td>';
		echo '<td>$'.$maxPairingsBudget.'</td>';
		echo '<td>'.$maxPairings.'</td>';
		echo '<td>'.($stopLossPercent).'%</td>';
		echo '<td>$'.($maxPairingsBudget * (($stopLossPercent) / 100)).'</td>';
		echo '<td>$'.number_format($cutOff,2,'.','').'</td>';
	
	echo '<td>'.number_format($profitToday,2).'</td>';
	echo '</tr>';
	echo '</table>';
flush();

	
	
}


$maxBudgetPerPairing 	= number_format(($spendBudget),2,'.','');

$maxSpendBudget 		= $spendBudget;

$spendBudgetAmt			= ($spendBudget);

$dateToCheck = date('Y-m-d');
// Change this to your desired date
									
// Create a DateTime object for the dateToCheck
$dateTime = new DateTime($dateToCheck);

// Loop through the last 7 days up to the dateToCheck


  $sqlUpdateBalance = "UPDATE coinData_user_balances SET 
        
        balance_amount_futures = '$futuresBalance',
        balance_amount_futures_profit = '$profitToday'
    WHERE balance_userID = '$user_userID'";
    
    if ($conn->query($sqlUpdateBalance) === TRUE) {
       
    } else {
        echo "Error: " . $conn->error;
    }

?>
