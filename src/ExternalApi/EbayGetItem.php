<?php


namespace App\ExternalApi;

use \DTS\eBaySDK\Shopping\Services;
use \DTS\eBaySDK\Shopping\Types;
use \DTS\eBaySDK\Shopping\Enums;

class EbayGetItem
{
    public function __construct($config, $userToken, $itemID)
    {
        $service = new Services\ShoppingService([
            'credentials' => $config['credentials'],
            'sandbox'      => true
        ]);

        $request = new Types\GetSingleItemRequestType();
        /**
         * Specify the item ID of the listing.
         */
        $request->ItemID = $itemID;
        /**
         * Specify that additional fields need to be returned in the response.
         */
        $request->IncludeSelector = 'ItemSpecifics,Variations,Compatibility,Details';
        /**
         * Send the request.
         */
        $response = $service->getSingleItem($request);

    }
}