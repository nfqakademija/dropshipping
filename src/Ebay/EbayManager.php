<?php


namespace App\Ebay;

use App\Ebay\EbayCredentials;
use App\ExternalApi\EbayBusinessPolicyService;
use App\ExternalApi\EbayServicesInterface;
use App\ExternalApi\EbayTradingService;
use App\ExternalApi\EbayAuth;
use App\ExternalApi\EbayMySelling;
use App\ExternalApi\EbayOrders;
use App\ExternalApi\EbayOrderSettings;

class EbayManager
{
    public $credentials;

    public $providers;

    public $tradingService;

    public $businessService;

    public function __construct(EbayCredentials $ebayCredentials, EbayServicesProvider $ebayServicesProvider)
    {
        $this->credentials = $ebayCredentials->getConfig('sandbox');
        $this->providers = $ebayServicesProvider;
    }

    public function tradingProvider(EbayTradingService $ebayTradingService)
    {
        $this->tradingService = $ebayTradingService;

        return $this->providers->services($this->tradingService, null);
    }

    public function businessPoliciesProvider(EbayBusinessPolicyService $ebayBusinessPolicyProvider, $token)
    {
        $this->businessService = $ebayBusinessPolicyProvider;
        return $this->providers->services($this->businessService, $token);
    }

    public function mySelling($token)
    {
        $mySellingClass = new EbayMySelling($this->tradingProvider($this->tradingService), $this->credentials);
        $mySelling = $mySellingClass->getMyItems($token);

        return $mySelling;
    }

    public function getItem($userToken, $itemID) {
        $myItem = (new EbayMySelling)->getItem($this->credentials, $userToken, $itemID);

        return $myItem;
    }

    public function getSessionLogin($entity, $userID)
    {
        $token = new EbayAuth($this->credentials, $entity, $userID);
        $createLogin = $token->getSessionID();
        return $createLogin;
    }

    public function getToken($entity, $userID)
    {
        $token = new EbayAuth($this->credentials, $entity, $userID);

        $getToken = $token->fetchMyToken($_SESSION['sessionid']);

        return $getToken;
    }

    public function getOrders($userToken)
    {
        $ebayOrders = new EbayOrders($this->tradingProvider($this->tradingService, $userToken), $this->credentials);

        $orderClass = $ebayOrders->getOrder($userToken);

        return $orderClass;
    }

    public function markShipped($userToken, $orderID, $type)
    {
        $ebayOrder = new EbayOrderSettings($this->tradingProvider($this->tradingService));
        $shipClass = $ebayOrder->markUnmark($userToken, $orderID, $type);

        return $shipClass;
    }

    public function feedBack($userToken, $transaction)
    {
        $ebayOrder = new EbayOrderSettings($this->tradingProvider($this->tradingService));

        $feedClass = $ebayOrder->insertFeedback(
            $userToken,
            $transaction
        );

        return $feedClass;
    }

    public function setTrackingNumber($userToken, $orderID, $orderLine, $number, $carrier) {
        $ebayOrder = new EbayOrderSettings($this->tradingProvider($this->tradingService));

        $feedClass = $ebayOrder->addTrackingNumber(
            $userToken,
            $orderID,
            $orderLine,
            $number,
            $carrier
        );

        return $feedClass;
    }

}