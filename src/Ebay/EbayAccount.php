<?php


namespace App\Ebay;


use App\ExternalApi\EbayAccounDetails;

class EbayAccount
{
    public $credentials;

    public function __construct(EbayManager $ebayManager)
    {
        $this->credentials = $ebayManager->credentials;
    }

    public function getAccountPolicies($userToken)
    {
        $policies = (new EbayAccounDetails)->getPolicies($this->credentials, $userToken);

        return $policies;
    }
}