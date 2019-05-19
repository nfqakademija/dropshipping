<?php


namespace App\ExternalApi;

use App\ExternalApi\EbayServicesInterface;
use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;


class EbayTradingService implements EbayServicesInterface
{
    public function getServices($config, $sandboxType = true)
    {
        $service = new Services\TradingService([
            'credentials' => $config['credentials'],
            'siteId'      => Constants\SiteIds::US,
            'sandbox' => $sandboxType
        ]);

        return $service;
    }
}