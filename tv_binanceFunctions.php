<?php

/*##########################################################################
DO NOT CHANGE THE BELOW AS THESE ARE FUNCTIONS USED BY THE APPLICATION.
© 2023 Zoe is licensed under Attribution-ShareAlike 4.0 International 
##########################################################################*/

if (isset($_GET['d']) && $_GET['d'] == '1') {
    $debug = 1;
} else {
    $debug = 0;
}
if($debug == 1)
{
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
}


function createLongOrder($symbol, $amountCoins, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "symbol=$symbol&side=BUY&positionSide=LONG&type=MARKET&quantity=$amountCoins&timestamp=$timestamp";

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v1/order?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the order request
    $ch = curl_init();

    // Set cURL options for the order request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    // Send the order request
    $response = curl_exec($ch);

    // Close cURL for the order request
    curl_close($ch);

    // Decode the JSON string of the order response to an array
    $result = json_decode($response, true);

    return $result;
}

	
function calculatePriceForDesiredProfitShort($desiredPercentage, $entryPrice, $rangeValue, $leverage) {
    // Step 1: Calculate the absolute profit based on the desired percentage
    $desiredProfit = ($desiredPercentage / 100) * $rangeValue;

    // Step 2: Translate that to 1x leverage
    $desiredProfit1x = $desiredProfit / $leverage;

    // Step 3: Determine the change in price for that profit at 1x leverage
    $priceChange1x = $desiredProfit1x * ($entryPrice / $rangeValue);

    // Step 4: Subtract that change to the Entry Price to get the new price
    $newPrice = $entryPrice - $priceChange1x;

    return $newPrice;
}

function createStopLoss($symbol, $stopPrice, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the stop-loss request
    $timestampStopLoss = round(microtime(true) * 1000);

    // Create the query string for the stop-loss order
    $stopLossQueryString = "symbol=$symbol&side=SELL&positionSide=LONG&closePosition=true&priceProtect=TRUE&type=STOP_MARKET&stopPrice=$stopPrice&timestamp=$timestampStopLoss";

    // Sign the stop-loss query string
    $signatureStopLoss = hash_hmac('sha256', $stopLossQueryString, $user_bnSecret);

    // Set the request URL for the stop-loss order
    $stopLossUrl = "https://fapi.binance.com/fapi/v1/order?$stopLossQueryString&signature=$signatureStopLoss";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the stop-loss request
    $chStopLoss = curl_init();

    // Set cURL options for the stop-loss request
    curl_setopt($chStopLoss, CURLOPT_URL, $stopLossUrl);
    curl_setopt($chStopLoss, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($chStopLoss, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chStopLoss, CURLOPT_POST, true);

    // Send the stop-loss request
    $stopLossResponse = curl_exec($chStopLoss);

    // Close cURL for the stop-loss request
    curl_close($chStopLoss);

    // Decode the JSON string of the stop-loss response to an array
    $stopLossResult = json_decode($stopLossResponse, true);

    return $stopLossResult;
}



function createShortStopLoss($symbol, $stopPrice, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the stop-loss request
    $timestampStopLoss = round(microtime(true) * 1000);

    // Create the query string for the stop-loss order
    $stopLossQueryString = "symbol=$symbol&side=BUY&positionSide=SHORT&closePosition=true&priceProtect=TRUE&type=STOP_MARKET&stopPrice=$stopPrice&timestamp=$timestampStopLoss";

    // Sign the stop-loss query string
    $signatureStopLoss = hash_hmac('sha256', $stopLossQueryString, $user_bnSecret);

    // Set the request URL for the stop-loss order
    $stopLossUrl = "https://fapi.binance.com/fapi/v1/order?$stopLossQueryString&signature=$signatureStopLoss";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the stop-loss request
    $chStopLoss = curl_init();

    // Set cURL options for the stop-loss request
    curl_setopt($chStopLoss, CURLOPT_URL, $stopLossUrl);
    curl_setopt($chStopLoss, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($chStopLoss, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chStopLoss, CURLOPT_POST, true);

    // Send the stop-loss request
    $stopLossResponse = curl_exec($chStopLoss);

    // Close cURL for the stop-loss request
    curl_close($chStopLoss);

    // Decode the JSON string of the stop-loss response to an array
    $stopLossResult = json_decode($stopLossResponse, true);

    return $stopLossResult;
}


function closeLongFuturesOrder($symbol, $coins, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "symbol=$symbol&side=SELL&positionSide=LONG&priceProtect=True&type=MARKET&quantity=$coins&timestamp=$timestamp";

    // Sign the query string using HMAC SHA256
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v1/order?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the order request
    $ch = curl_init();

    // Set cURL options for the order request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    // Send the order request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        // Handle error appropriately
        throw new Exception('Curl error: ' . curl_error($ch));
    }

    // Close cURL for the order request
    curl_close($ch);

    // Decode the JSON string of the order response to an array
    $result = json_decode($response, true);

    // Check for API response errors
    if (isset($result['code']) && $result['code'] < 0) {
        // Handle Binance API error
        throw new Exception('Binance API error: ' . $result['msg']);
    }

    return $result;
}


function closeShortFuturesOrder($symbol, $coins, $user_bnKey, $user_bnSecret) {
	// Create a timestamp for the request
	$timestamp = round(microtime(true) * 1000);

	// Create the query string
	$query_string = "symbol=$symbol&side=BUY&positionSide=SHORT&priceProtect=True&type=MARKET&quantity=$coins&timestamp=$timestamp";

	// Sign the query string
	$signature = hash_hmac('sha256', $query_string, $user_bnSecret);

	// Set the request URL
	$url = "https://fapi.binance.com/fapi/v1/order?$query_string&signature=$signature";

	// Set the request headers
	$headers = array("X-MBX-APIKEY: $user_bnKey");

	// Initialize cURL for the order request
	$ch = curl_init();

	// Set cURL options for the order request
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);

	// Send the order request
	$response = curl_exec($ch);

	// Close cURL for the order request
	curl_close($ch);

	// Decode the JSON string of the order response to an array
	$result = json_decode($response, true);

	return $result;
}

function createShortOrder($symbol, $amountCoins, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "symbol=$symbol&side=SELL&positionSide=SHORT&type=MARKET&quantity=$amountCoins&timestamp=$timestamp";

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v1/order?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the order request
    $ch = curl_init();

    // Set cURL options for the order request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    // Send the order request
    $response = curl_exec($ch);

    // Close cURL for the order request
    curl_close($ch);

    // Decode the JSON string of the order response to an array
    $result = json_decode($response, true);

    return $result;
}

function createTargetOrder($symbol, $targetPrice, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the take-profit request
    $timestampTakeProfit = round(microtime(true) * 1000);

    // Create the query string for the take-profit order
    $takeProfitQueryString = "symbol=$symbol&side=SELL&positionSide=LONG&closePosition=true&priceProtect=TRUE&type=TAKE_PROFIT_MARKET&stopPrice=$targetPrice&timestamp=$timestampTakeProfit";

    // Sign the take-profit query string
    $signatureTakeProfit = hash_hmac('sha256', $takeProfitQueryString, $user_bnSecret);

    // Set the request URL for the take-profit order
    $takeProfitUrl = "https://fapi.binance.com/fapi/v1/order?$takeProfitQueryString&signature=$signatureTakeProfit";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the take-profit request
    $chTakeProfit = curl_init();

    // Set cURL options for the take-profit request
    curl_setopt($chTakeProfit, CURLOPT_URL, $takeProfitUrl);
    curl_setopt($chTakeProfit, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($chTakeProfit, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chTakeProfit, CURLOPT_POST, true);

    // Send the take-profit request
    $takeProfitResponse = curl_exec($chTakeProfit);

    // Close cURL for the take-profit request
    curl_close($chTakeProfit);

    // Decode the JSON string of the take-profit response to an array
    $takeProfitResult = json_decode($takeProfitResponse, true);

    return $takeProfitResult;
}


function takeProfitShort($symbol, $coins, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the take-profit request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "symbol=$symbol&side=SELL&positionSide=SHORT&type=MARKET&quantity=$coins&timestamp=$timestamp";

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v1/order?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the take-profit request
    $ch = curl_init();

    // Set cURL options for the take-profit request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    // Send the take-profit request
    $response = curl_exec($ch);

    // Close cURL for the take-profit request
    curl_close($ch);

    // Decode the JSON string of the take-profit response to an array
    $result = json_decode($response, true);

    return $result;
}
function createShortTargetOrder($symbol, $targetPrice, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the take-profit request
    $timestampTakeProfit = round(microtime(true) * 1000);

    // Create the query string for the take-profit order
    $takeProfitQueryString = "symbol=$symbol&side=BUY&positionSide=SHORT&closePosition=true&priceProtect=TRUE&type=TAKE_PROFIT_MARKET&stopPrice=$targetPrice&timestamp=$timestampTakeProfit";

    // Sign the take-profit query string
    $signatureTakeProfit = hash_hmac('sha256', $takeProfitQueryString, $user_bnSecret);

    // Set the request URL for the take-profit order
    $takeProfitUrl = "https://fapi.binance.com/fapi/v1/order?$takeProfitQueryString&signature=$signatureTakeProfit";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the take-profit request
    $chTakeProfit = curl_init();

    // Set cURL options for the take-profit request
    curl_setopt($chTakeProfit, CURLOPT_URL, $takeProfitUrl);
    curl_setopt($chTakeProfit, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($chTakeProfit, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chTakeProfit, CURLOPT_POST, true);

    // Send the take-profit request
    $takeProfitResponse = curl_exec($chTakeProfit);

    // Close cURL for the take-profit request
    curl_close($chTakeProfit);

    // Decode the JSON string of the take-profit response to an array
    $takeProfitResult = json_decode($takeProfitResponse, true);

    return $takeProfitResult;
}

function takeProfitLong($symbol, $coins, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the take-profit request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "symbol=$symbol&side=SELL&positionSide=LONG&type=MARKET&quantity=$coins&timestamp=$timestamp";

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v1/order?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the take-profit request
    $ch = curl_init();

    // Set cURL options for the take-profit request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    // Send the take-profit request
    $response = curl_exec($ch);

    // Close cURL for the take-profit request
    curl_close($ch);

    // Decode the JSON string of the take-profit response to an array
    $result = json_decode($response, true);

    return $result;
}


	
function setStopPrice($api_key, $secret_key, $symbol, $side, $positionSide, $STprice) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = 'symbol=' . $symbol .
                    '&side=' . $side .
					'&positionSide=' . $positionSide .
					'&closePosition=true' .
					'&priceProtect=TRUE'.
                    '&type=STOP_MARKET' .
                    '&stopPrice=' . $STprice .
                   '&amp;timestamp=' . $timestamp;
					

                   
    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $secret_key);

    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/order?' . $query_string . '&signature=' . $signature;
//return $url;
    // Set the request headers
    $headers = array('X-MBX-APIKEY: ' . $api_key);

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result;
}
function futuresTakeProfitMarketOrder($api_key, $secret_key, $symbol, $side, $type, $positionSide, $quantity, $stopPrice) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = 'symbol=' . $symbol .
                    '&side=' . $side .
                    '&positionSide=' . $positionSide .
                    '&type=' . $type .
                    '&priceProtect=True' .
                    '&quantity=' . $quantity .
                    '&stopPrice=' . $stopPrice .
                    '&timestamp=' . $timestamp;

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $secret_key);

    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/order?' . $query_string . '&signature=' . $signature;

    // Set the request headers
    $headers = array('X-MBX-APIKEY: ' . $api_key);

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result;
}
						
function futuresOrder($api_key, $secret_key, $symbol, $side,$type,  $positionSide, $quantity) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = 'symbol=' . $symbol .
                    '&side=' . $side .
					'&positionSide=' . $positionSide .
                    '&priceProtect=True'.
                   '&type=' . $type .
                    '&quantity=' . $quantity .
					
                   '&amp;timestamp=' . $timestamp;
					

                   
    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $secret_key);

    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/order?' . $query_string . '&signature=' . $signature;
//return $url;
    // Set the request headers
    $headers = array('X-MBX-APIKEY: ' . $api_key);

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result;
}
					
function futuresMarketOrder($api_key, $secret_key, $symbol, $side,$type,  $positionSide, $quantity) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = 'symbol=' . $symbol .
                    '&side=' . $side .
					'&positionSide=' . $positionSide .
                    '&timeInForce=GTC'.
                   '&type=' . $type .
                    '&quantity=' . $quantity .
					
                   '&amp;timestamp=' . $timestamp;
					

                   
    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $secret_key);

    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/order?' . $query_string . '&signature=' . $signature;
//return $url;
    // Set the request headers
    $headers = array('X-MBX-APIKEY: ' . $api_key);

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result;
}

				
function futuresLimitOrder($api_key, $secret_key, $symbol, $side,$type,  $positionSide, $quantity, $LMTprice) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = 'symbol=' . $symbol .
                    '&side=' . $side .
					'&positionSide=' . $positionSide .
                    '&type=' . $type .
                    '&quantity=' . $quantity .
                    '&price=' . $LMTprice .
					'&timeInForce=GTC' .
                   '&amp;timestamp=' . $timestamp;
					

                   
    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $secret_key);

    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/order?' . $query_string . '&signature=' . $signature;
//return $url;
    // Set the request headers
    $headers = array('X-MBX-APIKEY: ' . $api_key);

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result;
}
function cancelOrderById($symbol, $orderId, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the cancel order request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "symbol=$symbol&orderId=$orderId&timestamp=$timestamp";

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v1/order?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the cancel order request
    $ch = curl_init();

    // Set cURL options for the cancel order request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // Use DELETE request to cancel order

    // Send the cancel order request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        // Handle the error as needed
        return null;
    }

    // Close cURL for the cancel order request
    curl_close($ch);

    // Decode the JSON string of the cancel order response to an array
    $result = json_decode($response, true);

    return $result;
}
						
function setTargetPrice($api_key, $secret_key, $symbol, $side, $positionSide, $TPprice) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = 'symbol=' . $symbol .
                    '&side=' . $side .
					'&positionSide=' . $positionSide .
					'&closePosition=true' .
                    '&priceProtect=TRUE'.
                   '&type=TAKE_PROFIT_MARKET' .
                    '&stopPrice=' . $TPprice .
                   '&amp;timestamp=' . $timestamp;
					

                   
    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $secret_key);

    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/order?' . $query_string . '&signature=' . $signature;
//return $url;
    // Set the request headers
    $headers = array('X-MBX-APIKEY: ' . $api_key);

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result;
}


function cancelOrders($api_key, $secret_key, $symbol) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = 'symbol=' . $symbol .
                  '&amp;timestamp=' . $timestamp;
					

                   
    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $secret_key);

    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/allOpenOrders?' . $query_string . '&signature=' . $signature;
//return $url;
    // Set the request headers
    $headers = array('X-MBX-APIKEY: ' . $api_key);

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result;
}

					
function setTargetLimitPrice($api_key, $secret_key, $symbol, $side, $positionSide, $TPprice, $quantity) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = 'symbol=' . $symbol .
                    '&side=' . $side .
					'&positionSide=' . $positionSide .
					'&type=TAKE_PROFIT' .
                    '&priceProtect=TRUE'.
                   '&quantity=' . $quantity .
                    '&stopPrice=' . $TPprice .
                    '&price=' . $TPprice .
                   '&amp;timestamp=' . $timestamp;
					

                   
    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $secret_key);

    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/order?' . $query_string . '&signature=' . $signature;
//return $url;
    // Set the request headers
    $headers = array('X-MBX-APIKEY: ' . $api_key);

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);


}


function setStopLimitPrice($api_key, $secret_key, $symbol, $side, $positionSide, $STprice, $quantity) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = 'symbol=' . $symbol .
                    '&side=' . $side .
					'&positionSide=' . $positionSide .
					 '&quantity=' . $quantity .
                    '&priceProtect=TRUE'.
                   '&type=STOP' .
                    '&stopPrice=' . $STprice .
                    '&price=' . $STprice .
                   '&amp;timestamp=' . $timestamp;
	
                   
    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $secret_key);

    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/order?' . $query_string . '&signature=' . $signature;
//return $url;
    // Set the request headers
    $headers = array('X-MBX-APIKEY: ' . $api_key);

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result;
}
function getSRprices($symbol, $interval, $limit)
 {
	 $getcandles = getKlines($symbol, $interval, $limit);
	 $arrayHighs = array();
	 $arrayLows = array();
	 
	 foreach($getcandles as $value)
	 {
	  array_push($arrayHighs, $value[2]);
	  array_push($arrayLows, $value[3]);
	 }
	 sort($arrayHighs);
	 arsort($arrayLows);
	 $getHigh = end($arrayHighs);
	  $getLow = end($arrayLows);
	  return array('High' => $getHigh, 'Low' => $getLow);
 }
	
function calculate_LIMIT_stopPrice($stopPrice,$side) {
     $goal = $stopPrice * 0.02;
   if($side == 'BUY')
	{
		$sellPrice = floatval($stopPrice) - floatval($goal);
	}
	elseif($side == 'SELL')
	{
		$sellPrice = floatval($stopPrice) + floatval($goal);
	}
    return $sellPrice;
}


function calculate_LIMIT_profitPrice($targetPrice,$side) {
     $goal = $targetPrice * 0.002;
   if($side == 'BUY')
	{
		$sellPrice = floatval($targetPrice) - floatval($goal);
	}
	elseif($side == 'SELL')
	{
		$sellPrice = floatval($targetPrice) + floatval($goal);
	}
    return $sellPrice;
}


function calculate_LIMIT_buyPrice($buyPrice,$side) {
     $goal = $buyPrice * 0.002;
   if($side == 'BUY')
	{
		$sellPrice = floatval($buyPrice) + floatval($goal);
	}
	elseif($side == 'SELL')
	{
	$sellPrice = floatval($buyPrice) - floatval($goal);
	}
    return $sellPrice;
}

function getlistLowArray($arrayLows, $current_price, $longLevels)
{
    // Sort the input array in descending order and remove duplicates
    rsort($arrayLows);
    $arrayLows = array_unique($arrayLows);

    // Initialize an empty array to hold the lowest values found
    $listLow = array();

    // Counter for the number of prices found
    $prices_found = 0;

    // Initialize the array pointer to the first element
    reset($arrayLows);

    // Loop through the array until the desired number of lowest values have been found or the end of the array is reached
    while ($prices_found < $longLevels && key($arrayLows) !== null) {
        // Get the current price
        $getPrice = current($arrayLows);

        // If the current price is less than the current price, add it to the list of lowest values and increment the counter
        if ($getPrice < $current_price) {
            array_push($listLow, $getPrice);
            $prices_found++;
        }

        // Move the array pointer to the next element
        next($arrayLows);
    }

    // Return the list of lowest values found
    return $listLow;
}

function getlistHighArray($arrayHighs, $current_price, $shortLevels)
{
    // Remove duplicates from the input array
    $arrayHighs = array_unique($arrayHighs);

    // Initialize a counter for the number of prices found
    $prices_found = 0;

    // Initialize the array pointer to the first element
    reset($arrayHighs);

    // Initialize an empty array to hold the highest values found
    $listHigh = array();

    // Loop through the array until the desired number of highest values have been found or the end of the array is reached
    while ($prices_found < $shortLevels && key($arrayHighs) !== null) {
        // Get the current price
        $getPrice = current($arrayHighs);

        // If the current price is greater than the current price, add it to the list of highest values and increment the counter
        if ($getPrice > $current_price) {
            array_push($listHigh, $getPrice);
            $prices_found++;
        }

        // Move the array pointer to the next element
        next($arrayHighs);
    }
		 asort($listHigh);
    // Return the list of highest values found
    return $listHigh;
}


function getPosition($symbol, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "symbol=$symbol&timestamp=$timestamp";

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v2/positionRisk?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the position request
    $ch = curl_init();

    // Set cURL options for the position request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Send the position request
    $response = curl_exec($ch);

    // Close cURL for the position request
    curl_close($ch);

    // Decode the JSON string of the position response to an array
    $result = json_decode($response, true);

    return $result;
}



function openAllOrders($user_bnKey, $user_bnSecret) {
    // Create a timestamp for the open orders request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "timestamp=$timestamp";

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v1/openOrders?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the open orders request
    $ch = curl_init();

    // Set cURL options for the open orders request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Send the open orders request (GET request)
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        // Handle the error as needed
        return null;
    }

    // Close cURL for the open orders request
    curl_close($ch);

    // Decode the JSON string of the open orders response to an array
    $result = json_decode($response, true);

    return $result;
}


function openOrders($symbol, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the open orders request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "symbol=$symbol&timestamp=$timestamp";

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v1/openOrders?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the open orders request
    $ch = curl_init();

    // Set cURL options for the open orders request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Send the open orders request (GET request)
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        // Handle the error as needed
        return null;
    }

    // Close cURL for the open orders request
    curl_close($ch);

    // Decode the JSON string of the open orders response to an array
    $result = json_decode($response, true);

    return $result;
}


function getCurrentPrice($symbol) {
    // Initialize cURL
    $ch = curl_init();

    // Set the cURL options
    curl_setopt($ch, CURLOPT_URL, "https://fapi.binance.com/fapi/v1/ticker/price?symbol=" . $symbol);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        // Handle the error as needed
        curl_close($ch); // Close cURL resource in case of an error
        return null;
    } else {
        // Decode the JSON response
        $data = json_decode($response, true);

        // Check if the response contains the 'price' field
        if (isset($data['price'])) {
            $currentPrice = $data['price'];
            curl_close($ch); // Close cURL resource on success
            return $currentPrice;
        } else {
            // Handle the case where 'price' field is not present
            curl_close($ch); // Close cURL resource when 'price' field is not present
            return null;
        }
    }
}


function getOpenOrders($user_bnKey, $user_bnSecret, $symbol) {
    $endpoint = "https://fapi.binance.com/fapi/v2/openOrders";
    $timestamp = time() * 1000;
    $query_string = "timestamp=$timestamp&symbol=$symbol";

    // Sign the request
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Complete endpoint URL
    $url = "$endpoint?$query_string&signature=$signature";

    // Prepare headers
    $headers = [
        "X-MBX-APIKEY: $user_bnKey"
    ];

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
									
function calculateProfitPercentage($profitAmount, $entryPrice, $spendBudget, $leverage, $currentPrice) {
	// Step 1: Determine the Price Difference
	$priceDifference = $currentPrice - $entryPrice;

	// Step 2 & 3: Calculate the Total Profit from a 1x Leverage Scenario for the Amount Spent
	$totalProfit1x = $priceDifference * ($spendBudget / $entryPrice);

	// Step 4: Calculate the Profit Taking into Account Leverage
	$totalProfitLeverage = $leverage * $totalProfit1x;

	// Step 5: Calculate the Percentage Profit
	$percentageProfit = ($totalProfitLeverage / $spendBudget) * 100;

	return number_format($percentageProfit,2);
}
function getOpenPositions($user_bnKey, $user_bnSecret) {
    $endpoint = "https://fapi.binance.com/fapi/v2/positionRisk";
    $timestamp = time() * 1000;
    $query_string = "timestamp=$timestamp";

    // Sign the request
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Complete endpoint URL
    $url = "$endpoint?$query_string&signature=$signature";

    // Prepare headers
    $headers = [
        "X-MBX-APIKEY: $user_bnKey"
    ];

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response
    $positions = json_decode($response, true);

    // Sort the positions in ascending order based on unrealisedProfit
    usort($positions, function($a, $b) {
        return $b['unRealizedProfit'] <=> $a['unRealizedProfit'];
    });

    return $positions;
}


function createShortLimitOrder($symbol, $halfCoins, $firstSale, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the limit order request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "symbol=$symbol&side=BUY&positionSide=SHORT&type=LIMIT&quantity=$halfCoins&price=$firstSale&timeInForce=GTC&timestamp=$timestamp";

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v1/order?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL for the limit order request
    $ch = curl_init();

    // Set cURL options for the limit order request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    // Send the limit order request
    $response = curl_exec($ch);

    // Close cURL for the limit order request
    curl_close($ch);

    // Decode the JSON string of the limit order response to an array
    $result = json_decode($response, true);
	
	 return $result;

}



function setLeverage($symbol, $leverage, $user_bnKey, $user_bnSecret) {
    // Create a timestamp for the request
    $timestamp = round(microtime(true) * 1000);

    // Create the query string
    $query_string = "symbol=$symbol&leverage=$leverage&timestamp=$timestamp";

    // Sign the query string
    $signature = hash_hmac('sha256', $query_string, $user_bnSecret);

    // Set the request URL
    $url = "https://fapi.binance.com/fapi/v1/leverage?$query_string&signature=$signature";

    // Set the request headers
    $headers = array("X-MBX-APIKEY: $user_bnKey");

    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    // Send the request
    $response = curl_exec($ch);

    // Close cURL
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result;
}


function futuresBalances($api_key, $secret_key, $asset)
{
    // Replace YOUR_API_KEY and YOUR_SECRET_KEY with your Binance API keys


    // Set the API endpoint for getting Futures account balance
$endpoint = '/fapi/v2/balance';

// Create a timestamp for the request
$timestamp = round(microtime(true) * 1000);

// Create the query string
$query_string = 'timestamp=' . $timestamp;

// Sign the query string
$signature = hash_hmac('sha256', $query_string, $secret_key);

// Set the request URL
$url = 'https://fapi.binance.com' . $endpoint . '?' . $query_string . '&signature=' . $signature;

// Set the request headers
$headers = array('X-MBX-APIKEY: ' . $api_key);

// Send the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

 // Decode the JSON string to an array
    $account = json_decode($response);
	
    // Check if the response contains the balances data
   
   

    // Loop through the balances array and find the balance for the specified asset
  

   foreach ($account as $item) {
    if ($item->asset === $asset) {
        $balance = $item->balance;
        // Do something with the balance, for example:
        return $balance;
    }
}
}

// Binance Futures Scripts


function getSymbolPrice($symbol) {
    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/ticker/price?symbol=' . $symbol;

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result['price'];
}



function calculateStopPrice($buyPrice,$side,$stopGoalPrice) {
     $goal = $buyPrice * $stopGoalPrice;
   if($side == 'BUY')
	{
		$sellPrice = floatval($buyPrice) - floatval($goal);
	}
	elseif($side == 'SELL')
	{
	$sellPrice = floatval($buyPrice) + floatval($goal);
	}
    return $sellPrice;
}


function calculateSellPrice($buyPrice,$side,$targetGoalPrice) {
     $goal = $buyPrice * $targetGoalPrice;
   if($side == 'BUY')
	{
		$sellPrice = floatval($buyPrice) + floatval($goal);
	}
	elseif($side == 'SELL')
	{
	$sellPrice = floatval($buyPrice) - floatval($goal);
	}
    return $sellPrice;
}

function get_binance_futures_top_trades($amount) {
    $url = "https://fapi.binance.com/fapi/v1/ticker/24hr";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    // Filter the array to only include USDT pairs
    $usdt_pairs = array_filter($result, function($item) {
        return strpos($item['symbol'], 'USDT') !== false;
    });
	//return $usdt_pairs;
    // Sort the array by trade in descending order
    usort($usdt_pairs, function($a, $b) {
        return $b['count'] <=> $a['count'];
    });

    $top_trades = array();
    for ($i = 0; $i < $amount; $i++) {
        $symbol = $usdt_pairs[$i]['symbol'];
        $trade = $usdt_pairs[$i]['count'];
        $top_trades[$symbol] = $trade;
    }
		arsort($top_trades);
    return $top_trades;
}

function get_binance_futures_top_volumes($amount) {
    $url = "https://fapi.binance.com/fapi/v1/ticker/24hr";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    // Filter the array to only include USDT pairs
    $usdt_pairs = array_filter($result, function($item) {
        return strpos($item['symbol'], 'USDT') !== false;
    });
	//return $usdt_pairs;
    // Sort the array by volume in descending order
    usort($usdt_pairs, function($a, $b) {
        return $b['volume'] <=> $a['volume'];
    });

    $top_volumes = array();
    for ($i = 0; $i < $amount; $i++) {
        $symbol = $usdt_pairs[$i]['symbol'];
        $volume = $usdt_pairs[$i]['volume'];
        $top_volumes[$symbol] = $volume;
    }
		arsort($top_volumes);
    return $top_volumes;
}


function calculatePriceForDesiredProfit($desiredPercentage, $entryPrice, $rangeValue, $leverage) {
    // Step 1: Calculate the absolute profit based on the desired percentage
    $desiredProfit = ($desiredPercentage / 100) * $rangeValue;

    // Step 2: Translate that to 1x leverage
    $desiredProfit1x = $desiredProfit / $leverage;

    // Step 3: Determine the change in price for that profit at 1x leverage
    $priceChange1x = $desiredProfit1x * ($entryPrice / $rangeValue);

    // Step 4: Add that change to the Entry Price to get the new price
    $newPrice = $entryPrice + $priceChange1x;

    return $newPrice;
}

function getTickSize($symbol) {
    $url = 'https://fapi.binance.com/fapi/v1/exchangeInfo';

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
   // Decode the JSON string to an array
    $data = json_decode($response, true);

    $tickSize = null;

    foreach ($data['symbols'] as $symbolItem) {
        $base = $symbolItem['baseAsset'];
        $quote = $symbolItem['quoteAsset'];
        $symbolName = $base . $quote;
        
        if ($symbolName == $symbol) {
            $filters = $symbolItem['filters'];
            
            foreach ($filters as $filter) {
                if ($filter['filterType'] == 'PRICE_FILTER') {
                    $tickSize = $filter['tickSize'];
                    break;
                }
            }
        }
        
        if ($tickSize !== null) {
            break;
        }
    }

    // Get the number of decimal places
    $decimalPlaces = strlen(substr(strrchr(rtrim($tickSize, '0'), '.'), 1));

    return $decimalPlaces;
}

function getLotSize($symbol) {
    $url = 'https://fapi.binance.com/fapi/v1/exchangeInfo';

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $data = json_decode($response, true);

    $stepSize = null;

    foreach ($data['symbols'] as $symbolItem) {
        $base = $symbolItem['baseAsset'];
        $quote = $symbolItem['quoteAsset'];
        $symbolName = $base . $quote;

        if ($symbolName == $symbol) {
            $filters = $symbolItem['filters'];

            foreach ($filters as $filter) {
                if ($filter['filterType'] == 'LOT_SIZE') {
                    $stepSize = $filter['stepSize'];
                    break;
                }
            }
        }

        if ($stepSize !== null) {
            break;
        }
    }

    // Get the number of decimal places
    $decimalPlaces = strlen(substr(strrchr(rtrim($stepSize, '0'), '.'), 1));

    return $decimalPlaces;
}

function get24hrChange($symbol) {
    // Set the request URL
    $url = 'https://fapi.binance.com/fapi/v1/ticker/24hr?symbol=' . $symbol;

    // Send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON string to an array
    $result = json_decode($response, true);

    return $result['priceChangePercent'];
}
?>
