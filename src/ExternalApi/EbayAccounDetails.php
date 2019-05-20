<?php


namespace App\ExternalApi;

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\BusinessPoliciesManagement\Services;
use \DTS\eBaySDK\BusinessPoliciesManagement\Types;


class EbayAccounDetails
{
    public function getPolicies($config, $userToken)
    {
        $service = new Services\BusinessPoliciesManagementService([
            'credentials' => $config['credentials'],
            'authToken'   => $userToken,
            'globalId'    => Constants\GlobalIds::US,
            'sandbox'     => true
        ]);

        /**
         * Create the request object.
         */

        $request = new Types\GetSellerProfilesRequest();
        /**
         * Send the request.
         */
        $response = $service->getSellerProfiles($request);
        /**
         * Output the result of calling the service operation.
         */
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