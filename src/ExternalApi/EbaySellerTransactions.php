<?php


namespace App\ExternalApi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Enums\ItemSortTypeCodeType;
use DTS\eBaySDK\Trading\Enums\ListingTypeCodeType;
use DTS\eBaySDK\Trading\Enums\OrderStatusCodeType;
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
            "SortingOrder"  => "Ascending",
            //"OrderRole"     => "Seller",
            "CreateTimeFrom"   => new \DateTime('2019-04-20'),
            "CreateTimeTo"   => date('Y-m-d'),
        );

        $getOrders = new Types\GetOrdersRequestType($args);
        $getOrders->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $getOrders->RequesterCredentials->eBayAuthToken = $this->token;
        $getOrders->OrderStatus = OrderStatusCodeType::C_ALL;
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
        $monthDaysGraph = [];
        $thirtyDaysAgo = new \DateTimeImmutable('-31 day');
        $today = new \DateTimeImmutable();
        $date = $thirtyDaysAgo;
        $monthPrices = [];

        foreach($orders->OrderArray->Order as $row) {
            $orderDate[] = $row->CreatedTime->format('Y-m-d');

            $monthPrices[] = [
                'date' => $row->CreatedTime->format('Y-m-d'),
                'values' => $row->Total->value
                ];
        }

        $priceArray = [];

        foreach($monthPrices as $prices) {
            if(array_key_exists($prices['date'], $priceArray)) {
                $priceArray[$prices['date']] += $prices['values'];
            } else {
                $priceArray[$prices['date']] = $prices['values'];
            }
        }

        while ($date <= $today) {
            $date = $date->modify('+1 day');
            $monthDaysGraph[] = $date->format('Y-m-d');
        }

        $getDate = array();

        foreach($monthDaysGraph as $ros => $valName) {

            if(in_array($valName, $orderDate)) {
                $salesCount = array_count_values($orderDate);
            } else {
                $salesCount = 0;
            }

            if(array_key_exists($valName, $priceArray)) {
                $salesPrices = $priceArray[$valName];
            } else {
                $salesPrices = 0;
            }

            $getDate['dates'.$ros] = [
                'dates'     => $valName,
                'values'    => ($salesCount > 0 ? $salesCount[$valName] : 0),
                'prices'    => $salesPrices
            ];

        }

        return $getDate;
    }

    public function countMonthProfit($entity)
    {
        $database = $entity->getConnection();
        $orders = $this->getOrders();
        $itemsData = [];
        $uploadProduct = [];
        $profitArray = [];
        foreach ($orders->OrderArray->Order as $row) {
            foreach ($row->TransactionArray->Transaction as $transaction) {
                $sql = 'SELECT * FROM ebay_item WHERE ebay_id = '.$transaction->Item->ItemID;
                $stmt = $database->prepare($sql);
                $stmt->execute();
                $itemsData = $stmt->fetchAll();
                $profit = null;

                foreach ($itemsData as $item) {
                    if ($item['ebay_id'] === $transaction->Item->ItemID) {
                        $aliSql = 'SELECT * FROM ali_express_item WHERE id = '.$item['product_id'];
                        $aliStmt = $database->prepare($aliSql);
                        $aliStmt->execute();
                        $uploadProduct = $aliStmt->fetchAll();
                    } else {
                        $uploadProduct = null;
                    }
                    foreach ($uploadProduct as $it) {
                        if ($it['id'] === $item['product_id']) {
                            $profit = $transaction->TransactionPrice->value * $transaction->QuantityPurchased - $it['price'];
                        } else {
                            $profit = 0;
                        }
                    }
                }
                $profitArray[] = [
                    'date' => $row->CreatedTime->format('Y-m-d'),
                    'profit' => $profit
                ];
            }
        }

        $priceArray = [];

        foreach ($profitArray as $prices) {
            if (array_key_exists($prices['date'], $priceArray)) {
                $priceArray[$prices['date']] += $prices['profit'];
            } else {
                $priceArray[$prices['date']] = $prices['profit'];
            }
        }

        $thirtyDaysAgo = new \DateTimeImmutable('-31 day');
        $today = new \DateTimeImmutable();
        $date = $thirtyDaysAgo;
        $monthDaysGraph = [];

        while ($date <= $today) {
            $date = $date->modify('+1 day');
            $monthDaysGraph[] = $date->format('Y-m-d');
        }

        $getDate = array();
        foreach ($monthDaysGraph as $ros => $valName) {
            if (array_key_exists($valName, $priceArray)) {
                $salesPrices = $priceArray[$valName];
            } else {
                $salesPrices = 0;
            }

            $getDate['date'.$ros] = [
                'date'     => $valName,
                'profit'    => (is_null($salesPrices) ? 0 : $salesPrices)
            ];

        }

        return $getDate;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getMonthSalesBonus(): array
    {
        $firstDay = new \DateTime(date("Y-m-d"));
        $firstDay->modify('first day of this month');
        $lastDay = new \DateTime(date("Y-m-d"));
        $lastDay->modify('last day of this month');
        $args = array(
            "SortingOrder"  => "Ascending",
            //"OrderRole"     => "Seller",
            "CreateTimeFrom"   => $firstDay->format('Y-m-d'),
            "CreateTimeTo"   => $lastDay->format('Y-m-d'),
        );
        $getOrders = new Types\GetOrdersRequestType($args);
        $getOrders->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $getOrders->RequesterCredentials->eBayAuthToken = $this->token;
        $getOrders->OrderStatus = OrderStatusCodeType::C_ALL;
        $getOrders->IncludeFinalValueFee = true;
        $response = $this->services->getOrders($getOrders);
        $lastMonthValues = [];

        foreach ($this->getLastMonth()->OrderArray->Order as $row => $key) {
            $lastMonthValues[] = $key->Total->value;
        }

        $thisMonthValues = [];

        foreach ($response->OrderArray->Order as $row => $key) {
            $thisMonthValues[] = $key->Total->value;
        }

        $lastMonthSoldItems = count($this->getLastMonth()->OrderArray->Order);
        $thisMonthSoldItems = count($response->OrderArray->Order);
        $countBonus = [
            'LastMonthSoldItems'     => $lastMonthSoldItems,
            'LastMonthSoldPrices'    => array_sum($lastMonthValues),
            'ThisMonthSoldItems'    => $thisMonthSoldItems,
            'ThisMonthSoldPrices'   => array_sum($thisMonthValues),
            'LeftToBonus'   => $lastMonthSoldItems - $thisMonthSoldItems,
            'TotalPercent'  => floor((100 * $thisMonthSoldItems) / $lastMonthSoldItems)
        ];

        return $countBonus;
    }
}