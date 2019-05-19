<?php


namespace App\ExternalApi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;

class EbayOrders
{
    public function getOrders($config, $userToken)
    {
        $service = new Services\TradingService([
            'credentials' => $config['credentials'],
            'siteId'      => Constants\SiteIds::US,
            'sandbox' => true
        ]);

        $request = new Types\GetMyeBaySellingRequestType();

        $args = array(
            "OrderStatus"   => "Completed",
            "OrderStatus"   => "All",
            "SortingOrder"  => "Ascending",
//            "SortingOrder"  => "Descending",
            //"OrderRole"     => "Seller",
            "CreateTimeFrom"   => new \DateTime('2019-05-01'),
            "CreateTimeTo"   => new \DateTime('2019-05-29'),
        );

        $getOrders = new Types\GetOrdersRequestType($args);
        $getOrders->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $getOrders->RequesterCredentials->eBayAuthToken = $userToken;
        $getOrders->IncludeFinalValueFee = true;
        $getOrders->Pagination = new Types\PaginationType();
        $getOrders->Pagination->EntriesPerPage = 3;
//        $getOrders->OrderIDArray = new Types\OrderIDArrayType();
        $getOrdersPageNum = 1;

        $response = $service->getOrders($getOrders);

        return $response;
    }
}