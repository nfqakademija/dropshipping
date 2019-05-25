<?php


namespace App\ExternalApi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;

class EbaySellerTransactions
{
    /**
     * @var Services\TradingService
     */
    public $services;

    public function getTransactions($config, $token)
    {
        $this->services = $config;
//        $args = array(
//            "OrderStatus"   => "Completed",
//            "OrderStatus"   => "All",
//            "SortingOrder"  => "Ascending",
//            //"OrderRole"     => "Seller",
//            "CreateTimeFrom"   => new \DateTime('2019-05-01'),
//            "CreateTimeTo"   => new \DateTime('2019-05-29'),
//        );
//        $getOrders = new Types\GetOrdersRequestType($args);
//        $getOrders->RequesterCredentials = new Types\CustomSecurityHeaderType();
//        $getOrders->RequesterCredentials->eBayAuthToken = $token;
//        $getOrders->IncludeFinalValueFee = true;
//        $getOrders->Pagination = new Types\PaginationType();
//        $getOrders->Pagination->EntriesPerPage = 15;
////        $getOrders->OrderIDArray = new Types\OrderIDArrayType();
//        $getOrdersPageNum = 1;

//        $request = new Types\GetSellerTransactionsRequest

        $request = new Types\GetMyeBaySellingRequestType();
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $token;
        $request->SellingSummary = new Types\ItemListCustomizationType();
        $request->ActiveList = new Types\ItemListCustomizationType();
        $request->SoldList = new Types\ItemListCustomizationType();
//        $request->IncludeCodiceFiscale = true;
//        $request->IncludeContainingOrder = true;
//        $request->IncludeFinalValueFee = true;
//        $request->ModTimeFrom = new \DateTime('2019-05-01Type();
//        $request = new Types\MyeBaySellingSummaryType();
//        $request = new Types\Myeb');
//        $request->ModTimeTo = new \DateTime('2019-05-29');
//        $request->NumberOfDays = 30;
//        $request->Pagination = new Types\PaginationType();
//        $request->Pagination->EntriesPerPage = 15;
//        $request->Pagination->PageNumber = 1;

//     $response = $this->services->getSellerTransactions($request);

        $response = $this->services->getMyeBaySelling($request);

        return $response;
    }
}