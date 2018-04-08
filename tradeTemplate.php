<?php

//autoload via composer
require '/vendor/autoload.php';

//api test balance
$api = new Binance\API("API_Key","Secret");

//primary coin
$coin1 = "USDT";

//secondary coin
$coin2 = "BTC";

//END OF USER INPUT

//just for html
$break = "<br>";

//get prices of all currencies 
$ticker = $api->prices();

//get user balances
$balances = $api->balances($ticker);

$currencyValue =$coin2.$coin1;

//calculations
$primaryCoin = $balances[$coin1]['available']/$ticker[$currencyValue];
$secondaryCoin = $balances[$coin2]['available'];
$overall = $secondaryCoin + $primaryCoin;
$ticks = $api->candlesticks($currencyValue, "1m");
$currentPrice =$ticker[$currencyValue];
$minBefore = 30;
$lastPrice = $ticks[count($ticks)-$minBefore]["close"];
$doubleLastPrice = $ticks[count($ticks)-($minBefore*2)]["close"];
$priceChange =(abs($currentPrice-$lastPrice)/$lastPrice);
$doublePriceChange =(abs($currentPrice-$doubleLastPrice)/$doubleLastPrice);

//for front end view
echo "{$coin2} owned: ".$secondaryCoin." {$coin2}";
echo $break;
echo "{$coin1} owned: ".$primaryCoin." {$coin2}";
echo $break;
echo "{$coin1} & {$coin2} owned: ".$overall." {$coin2}";
echo $break;
echo "Price of {$coin2}: ".$currentPrice;
echo $break;
echo $minBefore." ago price of {$coin2}: ".$lastPrice;
echo $break;
echo ($minBefore*2)." ago price of {$coin2}: ".$doubleLastPrice;
echo $break;
echo "percent change of price: ".($priceChange*100). "%";
echo $break;
echo "percent change of price double time ago: ".($doublePriceChange*100). "%";
echo $break;

//uncomment order to actually send buy/sell
if (($priceChange>.007)||($doublePriceChange>.012)){
	if ($lastPrice>$currentPrice){
		echo "buy";
		$quantity = ($overall*.1);
	   	echo $break;
	   	$quantity = round($quantity, 3);
	   	echo $quantity;
		//$order = $api->marketBuy($currencyValue, $quantity);
	}
	else{
		echo "sell";
		$quantity = ($overall*.1);
	   	echo $break;
	   	$quantity = round($quantity, 3);
	   	echo $quantity;
		//$order = $api->marketSell($currencyValue, $quantity);
	}
}
else{
	echo "stay";
}