<?php


namespace App\Ebay;

use App\Ebay\EbayCredentials;
use App\ExternalApi\EbayMySelling;

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
}