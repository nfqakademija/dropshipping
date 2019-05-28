<?php


namespace App\ExternalApi;

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\BusinessPoliciesManagement\Services;

class EbayBusinessPolicyService implements EbayServicesInterface
{
    /**
     * @param $config
     * @param null $token
     * @param bool $sandboxType
     * @return Services\BusinessPoliciesManagementService|mixed
     */
    public function getServices($config, $token = null, $sandboxType = true)
    {
        $service = new Services\BusinessPoliciesManagementService([
            'credentials' => $config['credentials'],
            'authToken'   => $token,
            'globalId'    => Constants\GlobalIds::US,
            'sandbox'     => $sandboxType
        ]);

        return $service;
    }
}