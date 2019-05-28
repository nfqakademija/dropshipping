<?php


namespace App\ExternalApi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Enums\ItemSortTypeCodeType;
use DTS\eBaySDK\Trading\Enums\ListingTypeCodeType;
use DTS\eBaySDK\Trading\Enums\OrderStatusFilterCodeType;
use DTS\eBaySDK\Trading\Enums\SortOrderCodeType;
use DTS\eBaySDK\Trading\Enums\TradingRoleCodeType;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;

class EbaySellerTransactions
{
    /**
     * @var Services\TradingService
     */
    private $services;

    private $token;

    /**
     * EbaySellerTransactions constructor.
     * @param $config
     * @param $token
     */
    public function __construct($config, $token)
    {
        $this->services = $config;
        $this->token = $token;
    }

    /**
     * @return Types\GetMyeBaySellingResponseType
     */
    public function getTransactions()
    {
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

//        $soldListing = new Types\GetSellingManagerSoldListingsRequestType();
//        $soldListing->RequesterCredentials = new Types\CustomSecurityHeaderType();
//        $soldListing->RequesterCredentials->eBayAuthToken = $token;
//        $soldListing->SaleDateRange = new Types\TimeRangeType();
//        $soldListing->SaleDateRange->TimeFrom = new \DateTime('2019-05-01');
//        $soldListing->SaleDateRange->TimeTo = new \DateTime('2019-05-31');
        $request = new Types\GetMyeBaySellingRequestType();
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $this->token;
        $request->SellingSummary = new Types\ItemListCustomizationType();
        $request->SellingSummary->Sort = ItemSortTypeCodeType::C_QUANTITY_SOLD;
        $request->ActiveList = new Types\ItemListCustomizationType();
        $request->SoldList = new Types\ItemListCustomizationType();
        $request->SoldList->OrderStatusFilter = OrderStatusFilterCodeType::C_ALL;
        $request->SoldList->ListingType = ListingTypeCodeType::C_FIXED_PRICE_ITEM;

        $response = $this->services->getMyeBaySelling($request);

        return $response;
    }

    public function getLastMonth()
    {
        $firstDay = new \DateTime( date("Y-m-d") );
        $firstDay->modify( 'first day of previous month' );

        $lastDay = new \DateTime( date("Y-m-d") );
        $lastDay->modify( 'last day of previous month' );

        $args = array(
            "OrderStatus"   => "Completed",
            "OrderStatus"   => "All",
            "SortingOrder"  => "Ascending",
//            //"OrderRole"     => "Seller",
//            "CreateTimeFrom"   => $firstDay->format('Y-m-d'),
//            "CreateTimeTo"   => $lastDay->format('Y-m-d'),
            "CreateTimeFrom"   => new \DateTime('2019-05-01'),
            "CreateTimeTo"   => new \DateTime('2019-05-27'),
        );

        $getOrders = new Types\GetOrdersRequestType($args);
        $getOrders->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $getOrders->RequesterCredentials->eBayAuthToken = $this->token;
        $getOrders->IncludeFinalValueFee = true;

        $response = $this->services->getOrders($getOrders);

        return $response;
    }

    public function getOrders()
    {
        $args = array(
            "OrderStatus"   => "Completed",
            "OrderStatus"   => "All",
            "SortingOrder"  => "Ascending",
            //"OrderRole"     => "Seller",
            "CreateTimeFrom"   => new \DateTime('2019-04-25'),
            "CreateTimeTo"   => date('Y-m-d'),
        );

        $getOrders = new Types\GetOrdersRequestType($args);
        $getOrders->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $getOrders->RequesterCredentials->eBayAuthToken = $this->token;
        $getOrders->IncludeFinalValueFee = true;

        $response = $this->services->getOrders($getOrders);

        return $response;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function countMonthOrders()
    {
        $orders = $this->getOrders();
        $orderDate = [];
        $orderDate2 = [];
        $monthDaysGraph = [];
        $thirtyDaysAgo = new \DateTimeImmutable('-31 day');
        $today = new \DateTimeImmutable();
        $date = $thirtyDaysAgo;
        $up = [];

        foreach($orders->OrderArray->Order as $row) {
            $orderDate[] = $row->CreatedTime->format('Y-m-d');

            $up[] = [
                'date' => $row->CreatedTime->format('Y-m-d'),
                 'values' => $row->Total->value
                ];
        }

        $new = [];

//        dump($up);

        foreach($up as $ro) {
            if(array_key_exists($ro['date'], $new)) {
                $new[$ro['date']] += $ro['values'];
            } else {
                $new[$ro['date']] = $ro['values'];
            }
        }
//        dump($new);

        $monthValues = array();


        $dateUniq = array();
        $sum = array();

        while ($date <= $today) {
            $date = $date->modify('+1 day');
            $monthDaysGraph[] = $date->format('Y-m-d');
        }

        $getDate = array();
        $getDate2 = array();

        foreach($monthDaysGraph as $ros => $name) {

            if(in_array($name, $orderDate)) {
                $salesCount = array_count_values($orderDate);
            } else {
                $salesCount = 0;
            }

            if(array_key_exists($name, $new)) {
                $sales2 = $new[$name];
            } else {
                $sales2 = 0;
            }

            $getDate['dates'.$ros] = [
                'dates'     => $name,
                'values'    => ($salesCount > 0 ? $salesCount[$name] : 0),
                'prices'    => $sales2
            ];

        }

        return $getDate;
    }
}