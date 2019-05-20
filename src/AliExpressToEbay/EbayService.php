<?php


namespace App\AliExpressToEbay;

use App\Ebay\EbayCredentials;
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;

class EbayService
{
    private $service;

    private $ebayCredentials;

    public function __construct()
    {
        $this->setCredentials();
    }

    public function setService($country)
    {
        if($country == "DE") {
            $siteId = Constants\SiteIds::DE;
        } elseif($country == "GB") {
            $siteId = Constants\SiteIds::GB;
        }

        $this->service = new Services\TradingService([
            'credentials' => $this->ebayCredentials,
            'sandbox'     => true,
            'siteId'      => $siteId
        ]);
    }

    public function getService()
    {
        return $this->service;
    }


    public function setCredentials()
    {
        $this->ebayCredentials = [
        'devId' => '0f7b9716-8cac-4b7f-b968-524b3e5d3f04',
        'appId' => 'MantasPu-codezipk-SBX-555b8f5d2-321e0c95',
        'certId' => 'SBX-55b8f5d2c56e-055f-41bd-a378-4287',
        ];
    }
}