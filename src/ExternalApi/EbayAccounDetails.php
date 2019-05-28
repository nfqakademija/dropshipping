<?php


namespace App\ExternalApi;

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\BusinessPoliciesManagement\Services;
use \DTS\eBaySDK\BusinessPoliciesManagement\Types;


class EbayAccounDetails
{
    /**
     * @var Services\BusinessPoliciesManagementService
     */
    private $service;

    /**
     * @param $config
     * @return mixed
     */
    public function getPolicies($config)
    {
        $this->service = $config;
        $request = new Types\GetSellerProfilesRequest();
        $response = $this->service->getSellerProfiles($request);

        if ($response->ack !== 'Success') {
            if (isset($response->errorMessage)) {
                foreach ($response->errorMessage->error as $error) {
                    printf("Error: %s\n", $error->message);
                }
            }
        }

        return $response;
    }
}