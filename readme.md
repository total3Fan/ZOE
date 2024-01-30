Application
================================================================

ZOE: Is an open source project that enables you to take Tradingview Alerts using any indicator, have data pass to a webhook then trades place on binancefutures listings.

Objective of placing this source onto GitHub is to encourage others to offer multiple source code variations such as Python and others. Also additional exchanges to binancefutures which it currently offers.

ZOE will take the tradingview alerts, then place them within a database table named 'tradingview_alerts' and have 3 cron jobs to carry out the following processes:

1. Scan for trades within tradingview_alerts
2. Place trades onto orderbook within binancefutures with both TargetPoints and StopLoss
3. Scan current trades to see if % profit has been achieved to move the StopLoss up to secure profits.
4. Scan order book for any dead trades sitting and clear them automatically.
5. Scan trades and if a LONG is currently in play and a SHORT is given, ZOE will automatically remove the long and place the SHORT.

Tradingview Indicator
================================================================

ZOE has her own indicator if desired: Details can be found on the link below

https://www.tradingview.com/script/5mhMn5Di-Zoe/ 

If you want to use your own Indicator the following information is requred to be sent to ZOE tradingview alerts:

LONG,BTCUSDT.P,42822,240,43953,46536,41370
SHORT,BTCUSDT.P,42822,240,43953,46536,41370

Indicator for ZOE uses the following format:

LONG,{{ticker}},{{close}},{{interval}},{{plot_5}},{{plot_9}},{{plot_2}}
SHORT,{{ticker}},{{close}},{{interval}},{{plot_5}},{{plot_9}},{{plot_2}}

<trade>,<symbol>,<price>,<timeline>,<targetPrice>,<High Fib>,<Low Fib>


Files within ZOE:
================================================================

----tv_dbConfig.php  // database params and BinanceFutures API Details

----tv_webhook.php   // Webhook file, path to be given to Tradingview Alerts Webhook URL

----tv_zoeProcess.php // Process file to check database table for any trades that are currently desired to be placed onto the orderbook

----tv_budgets.php // User preferences of settings for the usage of ZOE on trades, StopLoss information, TargetPoint information, Leverage desired and more.

----tv_positions.php // Process file for ZOE to check current open trades and move StopLoss if trade has met % increase in accordance with tv_budgets.php settings.

----tv_clean.php // Process file to remove dead trades, if no SL or TP given it will automatically place them, useful when manually creating trades.

----tv_binanceFutures.php // functions for ZOE with binancefutures.

----tv_longcode.php // Include file to list Long trades with tv_zoeProcess.php

----tv_shortcode.php // Include file to list Short trades with tv_zoeProcess.php

--tv_cronJobs.txt // cron jobs to be created for automation

--tv_mysqlTable.txt // Create Table code to generate the table required for ZOE to be named tradingview_alerts

Setup:
================================================================

Recommended Apache 2.0, PHP8.1+, MariaDB/MySQL

Cron Enabled


Installation:
================================================================

1. Create a folder on your php web server for ZOE location
2. Place files within folder 
3. Configure tv_dbConfig.php with your binancefutures API information
4. Configure preferences in tv_budgets.php for tradingview
5. Ensure BinanceFutures settings are as follows:

a) Position Mode: Hedge Mode
b) API Futures Trading Enabled
c) Balance held on Binance Futures

6. Ensure server that is initiating the trades within binance is in a location accepted by Binance and their regulations.

Security Awareness
================================================================

Because you are giving a publicly accessable URL to TradingView for your webhook, I recommend the following:

1. Rename the tv_webhook.php file to something unique e.g. tv_s498fWSjkr.php
2. When placing files into a folder on your server, again I recommend using a folder name that is again unique e.g. '_d_4fjS9Kdf4/'
3. You can restrict access by only allowing TradingView IP addresses to push data to your webhook.
   
(Details here: https://www.tradingview.com/support/solutions/43000529348-about-webhooks/ )

Disclaimer of Usage
================================================================

By using this software, you, the user, acknowledge and agree to the following terms:

Usage at Own Risk: You understand and agree that you are using this software entirely at your own risk. The developers of this software shall not be liable for any direct, indirect, incidental, consequential, or punitive damages arising out of your access to, use of, inability to use, or reliance on the software.


Responsibility for Reviewing Source Code: You acknowledge that it is your responsibility to review the source code of the software prior to its usage and during its usage. This review is crucial to understand the functioning of the software, identify any potential risks or errors, and ensure that it meets your requirements and expectations.


No Warranties: This software is provided "as is," without warranty of any kind, express or implied, including but not limited to the warranties of merchantability, fitness for a particular purpose, and non-infringement. In no event shall the authors or copyright holders be liable for any claim, damages, or other liability, whether in an action of contract, tort, or otherwise, arising from, out of, or in connection with the software or the use or other dealings in the software.


Compliance with Laws: You agree to use the software in compliance with all applicable laws, regulations, and policies.


Modification of Terms: The terms of this disclaimer are subject to change at any time, and it is your responsibility to review these terms regularly to ensure your continued compliance and agreement.


By continuing to access or use this software, you signify your acceptance of these terms.


License
================================================================
© 2023 Zoe is licensed under Attribution-ShareAlike 4.0 International 



Donations Welcome
================================================================

If you would like to support this development please do so using crypto of your choice below, thank you in advance:

Algorand (ALG)

2GSHYDYHW5PPGZNJM6SEJZZCZ4K6XLFD7D7MQGAVPYMQ2YPQ5MGZ656ILU

Avalanche (AVAX)

0x05d4d6b5d0e8083739ec6f7c2d38c4df23e30378

Bitcoin (BTC)

bc1qmej54v5ah7dal04ynu9wlx3twmvme526zxhtcg 

Dogecoin (DOGE)

DKtWr67b8c31BB9U5NLcUx28HtKvxpZHW7

XRP

rD3Spsysr8mY3tuUSuTyh7okSE7B4hs4PM




