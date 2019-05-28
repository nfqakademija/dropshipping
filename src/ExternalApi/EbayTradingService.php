<?php


namespace App\ExternalApi;

use App\ExternalApi\EbayServicesInterface;
use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;


class EbayTradingService implements EbayServicesInterface
{
    public $userToken;

    /**
     * @param $config
     * @param null $token
     * @param bool $sandboxType
     * @return Services\TradingService|mixed
     */
    public function getServices($config, $token = null, $sandboxType = true)
    {

        $service = new Services\TradingService([
            'credentials' => $config['credentials'],
            'siteId'      => Constants\SiteIds::US,
            'sandbox' => $sandboxType
        ]);

        return $service;
    }
}