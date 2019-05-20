<?php


namespace App\ExternalApi;

use App\ExternalApi\EbayServicesInterface;
use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;

class EbayOrders
{
    private $services;

    private $config;

    public function __construct($services, $config)
    {
        $this->services = $services;
        $this->config = $config;
    }

    public function getOrders($userToken)
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
        $getOrders->Pagination->EntriesPerPage = 3;
//        $getOrders->OrderIDArray = new Types\OrderIDArrayType();
        $getOrdersPageNum = 1;

        $response = $this->services->getOrders($getOrders);

        return $response;
    }
}