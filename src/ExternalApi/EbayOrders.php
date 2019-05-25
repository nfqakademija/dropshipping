<?php


namespace App\ExternalApi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;

class EbayOrders
{

    /**
     * @var Services\TradingService
     */
    public $services;

    /**
     * EbayOrders constructor.
     * @param $services
     */
    public function __construct($services)
    {
        $this->services = $services;
    }


    /**
     * @param $userToken
     * @return Types\GetOrdersResponseType
     * @throws \Exception
     */
    public function getOrder($userToken)
    {

        $args = array(
            "OrderStatus"   => "Completed",
            "OrderStatus"   => "All",
            "SortingOrder"  => "Ascending",
            //"OrderRole"     => "Seller",
            "CreateTimeFrom"   => new \DateTime('2019-05-01'),
            "CreateTimeTo"   => new \DateTime('2019-05-29'),
        );

        $getOrders = new Types\GetOrdersRequestType($args);
        $getOrders->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $getOrders->RequesterCredentials->eBayAuthToken = $userToken;
        $getOrders->IncludeFinalValueFee = true;
        $getOrders->Pagination = new Types\PaginationType();
        $getOrders->Pagination->EntriesPerPage = 15;
//        $getOrders->OrderIDArray = new Types\OrderIDArrayType();
        $getOrdersPageNum = 1;

        $response = $this->services->getOrders($getOrders);

        return $response;
    }
}