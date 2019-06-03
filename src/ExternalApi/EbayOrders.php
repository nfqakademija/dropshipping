<?php


namespace App\ExternalApi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Enums\OrderStatusCodeType;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;

class EbayOrders
{

    /**
     * @var Services\TradingService
     */
    public $services;

    public $entity;

    /**
     * EbayOrders constructor.
     * @param $services
     * @param $entityManager
     */
    public function __construct($services, $entityManager)
    {
        $this->services = $services;
        $this->entity   = $entityManager;
    }


    /**
     * @param $userToken
     * @return Types\GetOrdersResponseType
     * @throws \Exception
     */
    public function getOrder($userToken)
    {
        $database = $this->entity->getConnection();
        $args = array(
            "SortingOrder"  => "Ascending",
            "CreateTimeFrom"   => new \DateTime('2019-05-01'),
            "CreateTimeTo"   => new \DateTime('2019-06-15'),
        );
        $getOrders = new Types\GetOrdersRequestType($args);
        $getOrders->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $getOrders->RequesterCredentials->eBayAuthToken = $userToken;
        $getOrders->IncludeFinalValueFee = true;
        $getOrders->Pagination = new Types\PaginationType();
        $getOrders->OrderStatus = OrderStatusCodeType::C_ALL;
        $getOrders->Pagination->EntriesPerPage = 50;
        $response = $this->services->getOrders($getOrders);
        $itemsData = [];
        $orderArray = [];
        $product = [];

        foreach ($response->OrderArray->Order as $order) {
            $type = null;
            foreach ($order->TransactionArray->Transaction as $transaction) {
                $sql = 'SELECT * FROM ebay_item WHERE ebay_id = '.$transaction->Item->ItemID;
                $stmt = $database->prepare($sql);
                $stmt->execute();
                $itemsData = $stmt->fetchAll();
                $profit = null;

                foreach ($itemsData as $row) {
                    if ($row['ebay_id'] === $transaction->Item->ItemID) {
                        $aliSql = 'SELECT * FROM ali_express_item WHERE id = '.$row['product_id'];
                        $aliStmt = $database->prepare($aliSql);
                        $aliStmt->execute();
                        $uploadProduct = $aliStmt->fetchAll();
                        $type = 'aliexpress';
                    } else {
                        $uploadProduct = null;
                        $type = 'amazon';
                    }
                    foreach ($uploadProduct as $it) {
                        if ($it['id'] === $row['product_id']) {
                            $product[$row['origin']] = $it;
                            $profit = $transaction->TransactionPrice->value * $transaction->QuantityPurchased - $it['price'];
                        } else {
                            $product = null;
                            $profit = null;
                        }
                    }
                }
            }
            $orderArray[] = [
              'order' => $order,
              'profit' => $profit,
              'type' => $type
            ];
        }
//        dump($orderArray);

        return $orderArray;
    }
}