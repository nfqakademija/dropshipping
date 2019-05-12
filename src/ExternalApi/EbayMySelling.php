<?php


namespace App\ExternalApi;

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;
use \DTS\eBaySDK\Shopping\Services as ShopServ;
use \DTS\eBaySDK\Shopping\Types as ShopTypes;
use \DTS\eBaySDK\Shopping\Enums as ShopEnums;

class EbayMySelling
{
    public function getMyItems($config, $userOauth)
    {
        $service = new Services\TradingService([
            'credentials' => $config['credentials'],
            'siteId'      => Constants\SiteIds::US,
            'sandbox' => true
        ]);

        $request = new Types\GetMyeBaySellingRequestType();
        /**
         * An user token is required when using the Trading service.
         */
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $userOauth;

        $request->ActiveList = new Types\ItemListCustomizationType();
        $request->ActiveList->Include = true;
        $request->ActiveList->Pagination = new Types\PaginationType();
        $request->ActiveList->Pagination->EntriesPerPage = 10;
        $request->ActiveList->Sort = Enums\ItemSortTypeCodeType::C_CURRENT_PRICE_DESCENDING;
        $pageNum = 1;
        $request->ActiveList->Pagination->PageNumber = $pageNum;
        $response = $service->getMyeBaySelling($request);

        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                    printf(
                        "%s: %s\n%s\n\n",
                        $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                        $error->ShortMessage,
                        $error->LongMessage
                    );
            }
        } else {
            return $response->ActiveList->ItemArray->Item;
        }

    }

    public function getItem($config, $userToken, $itemID)
    {
        $service = new Services\TradingService([
            'credentials' => $config['credentials'],
            'siteId'      => Constants\SiteIds::US,
            'sandbox' => true
        ]);

        $request = new Types\GetItemRequestType();

        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $userToken;

        $request->ItemID = $itemID;
        $request->IncludeItemSpecifics = true;
        $request->IncludeItemCompatibilityList = true;

        $response = $service->getItem($request);


        $item = new EbayGetItem($config, $userToken, $itemID);

//        var_dump($item);
        return $response;


    }
}