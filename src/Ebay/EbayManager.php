<?php


namespace App\Ebay;

use App\Ebay\EbayCredentials;
use App\ExternalApi\EbayAuth;
use App\ExternalApi\EbayMySelling;
use App\ExternalApi\EbayOrders;
use App\ExternalApi\EbayOrderSettings;

class EbayManager
{
    public $credentials;

    public function __construct(EbayCredentials $ebayCredentials)
    {
        $this->credentials = $ebayCredentials->getConfig('sandbox');
    }

    public function mySelling($userOauthToken)
    {
        $mySelling = (new EbayMySelling)->getMyItems($this->credentials, $userOauthToken);

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
        $orderClass = (new EbayOrders)->getOrders($this->credentials, $userToken);

        return $orderClass;
    }

    public function markShipped($userToken, $orderID, $type)
    {
        $shipClass = (new EbayOrderSettings)->markUnmark($this->credentials, $userToken, $orderID, $type);

        return $shipClass;
    }

}