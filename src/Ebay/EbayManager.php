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

    /**
     * EbayManager constructor.
     * @param \App\Ebay\EbayCredentials $ebayCredentials
     * @param EbayServicesProvider $ebayServicesProvider
     * @throws \Exception
     */
    public function __construct(EbayCredentials $ebayCredentials, EbayServicesProvider $ebayServicesProvider)
    {
        $this->credentials = $ebayCredentials->getConfig('sandbox');
        $this->providers = $ebayServicesProvider;
    }

    /**
     * @param EbayTradingService $ebayTradingService
     * @return mixed
     */
    public function tradingProvider(EbayTradingService $ebayTradingService)
    {
        $this->tradingService = $ebayTradingService;

        return $this->providers->services($this->tradingService, null);
    }

    /**
     * @param EbayBusinessPolicyService $ebayBusinessPolicyProvider
     * @param $token
     * @return mixed
     */
    public function businessPoliciesProvider(EbayBusinessPolicyService $ebayBusinessPolicyProvider, $token)
    {
        $this->businessService = $ebayBusinessPolicyProvider;
        return $this->providers->services($this->businessService, $token);
    }

    /**
     * @param $token
     * @param $entityManager
     * @return array
     */
    public function mySelling($token, $entityManager)
    {
        $mySellingClass = new EbayMySelling($this->tradingProvider($this->tradingService), $entityManager);
        $mySelling = $mySellingClass->getMyItems($token);

        return $mySelling;
    }

    /**
     * @param $userToken
     * @param $itemID
     * @return \DTS\eBaySDK\Trading\Types\GetItemResponseType
     */
    public function getItem($userToken, $itemID)
    {
        $myItem = (new EbayMySelling)->getItem($this->credentials, $userToken, $itemID);

        return $myItem;
    }

    /**
     * @param $entity
     * @param $userID
     * @return string
     */
    public function getSessionLogin($entity, $userID)
    {
        $token = new EbayAuth($this->credentials, $entity, $userID);
        $createLogin = $token->getSessionID();
        return $createLogin;
    }

    /**
     * @param $entity
     * @param $userID
     */
    public function getToken($entity, $userID)
    {
        $token = new EbayAuth($this->credentials, $entity, $userID);

        $getToken = $token->fetchMyToken($_SESSION['sessionid']);

        return $getToken;
    }

    /**
     * @param $userToken
     * @return \DTS\eBaySDK\Trading\Types\GetOrdersResponseType
     * @throws \Exception
     */
    public function getOrders($userToken, $entityManager)
    {
        $ebayOrders = new EbayOrders(
            $this->tradingProvider($this->tradingService, $userToken),
            $entityManager
        );

        $orderClass = $ebayOrders->getOrder($userToken);

        return $orderClass;
    }

    /**
     * @param $userToken
     * @param $orderID
     * @param $type
     * @return \DTS\eBaySDK\Trading\Types\CompleteSaleResponseType
     */
    public function markShipped($userToken, $orderID, $type)
    {
        $ebayOrder = new EbayOrderSettings($this->tradingProvider($this->tradingService));
        $shipClass = $ebayOrder->markUnmark($userToken, $orderID, $type);

        return $shipClass;
    }

    /**
     * @param $userToken
     * @param $transaction
     * @return \DTS\eBaySDK\Trading\Types\LeaveFeedbackResponseType
     */
    public function feedBack($userToken, $transaction)
    {
        $ebayOrder = new EbayOrderSettings($this->tradingProvider($this->tradingService));

        $feedClass = $ebayOrder->insertFeedback(
            $userToken,
            $transaction
        );

        return $feedClass;
    }

    /**
     * @param $userToken
     * @param $orderID
     * @param $orderLine
     * @param $number
     * @param $carrier
     * @return \DTS\eBaySDK\Trading\Types\CompleteSaleResponseType
     */
    public function setTrackingNumber($userToken, $orderID, $orderLine, $number, $carrier)
    {
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