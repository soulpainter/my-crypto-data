<?php

require_once('bootstrap.php');

$allHoldings = array();

$allHoldings['bittrex'] = $bittrex->getCoinBalances();

#$coinbaseHoldings = array('BTC','ETH','BCH','LTC');

#$krakenHoldings = array('XRP','XMR','MLN','XLM','GNO','ETC',
#                        'EOS','DOGE','DASH','BCH','BTC','REP');

$allHoldings['jaxx'] = array(
  'BTC' => 0.85213173,
  'BCH' => 1.21864032,
  'ETH' => 6.83051838,
  'DASH' => 5,
  'LTC' => 9.37812472,
  'ZEC' => 2.80552706,
  'ETC' => 4.99,
  'DOGE' => 166411.73,
  'EOS' => 155.26323,
  'GNO' => 2.01587,
  'ICN' => 99.8,
  'MLN' => 5.83505,
  'REP' => 22.2352 
);

#$cryptopiaHoldings = array('DBG','NOTE','GAME','NVC','UIS','XVG');
#$allHoldings['cryptopia'] = $cryptopia->getCoinBalances();

#print_r($allHoldings);
#exit;

$sums = array();
foreach (array_keys($allHoldings['bittrex'] + $allHoldings['jaxx']) as $key) {
    $sums[$key] = (isset($allHoldings['bittrex'][$key]) ? $allHoldings['bittrex'][$key] : 0) + (isset($allHoldings['jaxx'][$key]) ? $allHoldings['jaxx'][$key] : 0);
}
$allHoldings = $sums;
#print_r($sums);
#exit;

$json = $cryptoCompare->getCoinListJson();
$coinList = json_decode($json, true);

$priceString = implode(',', array_keys($allHoldings));

$priceJson = $cryptoCompare->getUSDPriceData($priceString);
$usdPrices = json_decode($priceJson, true);

$totalHoldingsValueUSD = 0;

foreach($coinList['Data'] as $symbol=>$coinData)
{
  if(array_key_exists($symbol, $allHoldings))
  {
    $usdValue = array_key_exists($symbol,$usdPrices) ? 1/$usdPrices[$symbol] : 0;
    $totalValue = $usdValue * $allHoldings[$symbol];
    $totalHoldingsValueUSD += $totalValue;
    $totalValue = number_format($totalValue, 2);
    $log->addDebug('Found', ['symbol' => $symbol, 'usd_value' => $usdValue, 'total_coins' => $allHoldings[$symbol], 'total_usd_value' => $totalValue]);
    unset($allHoldings[$symbol]);
  }
}
$log->addDebug('TotalHoldingsValueUSD', ['total_holdings_usd_value' => number_format($totalHoldingsValueUSD, 2)]);

if(count($allHoldings) > 0)
{
  $log->addWarning('NotFound:', $allHoldings);
}

