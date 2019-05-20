<?php


namespace App\Ebay;

use App\ExternalApi\EbayServicesInterface;
use App\ExternalApi\EbayTradingService;

class EbayServicesProvider
{
    public function tradingServices(EbayServicesInterface $ebayServices, $config)
    {
        return $ebayServices->getServices($config, true);
    }
}