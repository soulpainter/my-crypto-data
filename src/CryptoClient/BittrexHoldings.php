<?php
namespace CryptoClient;

class BittrexHoldings
{
  private $rawData;

  private $holdings = array();

  public function __construct($rawData)
  {
    $this->rawData = $rawData;
  }

  private function compactCoins($balances)
  {
    foreach($balances['result'] as $coin)
    {
      if($coin['Balance'] > 0)
      {
        $this->coinBalances[$coin['Currency']] = $coin['Balance'];
      }
    }
  }

  public function getCoinBalances($accountBalances)
  {
    $this->compactCoins($accountBalances);
    return $this->coinBalances;
  }
}

#$bittrex = new BittrexClient($config['BITTREX_API_KEY'], $config['BITTREX_API_SECRECT'], new Client());
#print_r($bittrex->getCoinBalances());








