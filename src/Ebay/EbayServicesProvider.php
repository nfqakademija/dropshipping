<?php


namespace App\Ebay;

use App\ExternalApi\EbayServicesInterface;

class EbayServicesProvider
{
    public $credentials;

    public $userToken;

    /**
     * EbayServicesProvider constructor.
     * @param EbayCredentials $ebayCredentials
     * @throws \Exception
     */
    public function __construct(EbayCredentials $ebayCredentials)
    {
        $this->credentials = $ebayCredentials->getConfig('sandbox');
    }

    /**
     * @param EbayServicesInterface $ebayServices
     * @param $token
     * @return mixed
     */
    public function services(EbayServicesInterface $ebayServices, $token)
    {
        $this->userToken = $token;

        return $ebayServices->getServices($this->credentials, $token, true);
    }
}