<?php


namespace App\ExternalApi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;

class EbayUserFeedback
{
    /**
     * @var Services\TradingService
     */
    public $services;

    /**
     * @param $config
     * @param $token
     * @return mixed
     */
    public function getFeedback($config, $token)
    {
        $this->services = $config;

        $request = new Types\GetFeedbackRequestType();
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $token;
        $request->DetailLevel = ['ReturnAll'];
        $response = $this->services->getFeedback($request);

        return $response;
    }
}