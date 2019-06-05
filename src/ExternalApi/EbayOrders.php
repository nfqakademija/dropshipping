<?php


namespace App\ExternalApi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Enums\OrderStatusCodeType;
use DTS\eBaySDK\Trading\Enums\SortOrderCodeType;
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
     * @return array
     * @throws \Exception
     */
    public function getOrder($userToken): array
    {
        $database = $this->entity->getConnection();
        $args = array(
            "CreateTimeFrom"   => new \DateTime('2019-05-01'),
            "CreateTimeTo"   => new \DateTime('2019-06-15'),
        );
        $getOrders = new Types\GetOrdersRequestType($args);
        $getOrders->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $getOrders->RequesterCredentials->eBayAuthToken = $userToken;
        $getOrders->IncludeFinalValueFee = true;
        $getOrders->Pagination = new Types\PaginationType();
        $getOrders->SortingOrder = SortOrderCodeType::C_DESCENDING;
        $getOrders->OrderStatus = OrderStatusCodeType::C_ALL;
        $getOrders->Pagination->EntriesPerPage = 50;
        $response = $this->services->getOrders($getOrders);
        $itemsData = [];
        $orderArray = [];
        $product = [];
        $itemID = [];

        foreach ($response->OrderArray->Order as $order) {
            $type = null;
            foreach ($order->TransactionArray->Transaction as $transaction) {
                $itemID[] = $transaction->Item->ItemID;
                $sql = 'SELECT * FROM ebay_item WHERE ebay_id = '.$transaction->Item->ItemID;
                $stmt = $database->prepare($sql);
                $stmt->execute();
                $itemsData = $stmt->fetchAll();
                $profit = null;
                $aliImage = null;
                $itemPicture = $this->getImage($userToken, $transaction->Item->ItemID);

                foreach ($itemsData as $row) {
                    if ($row['ebay_id'] === $transaction->Item->ItemID) {
                        $aliSql = 'SELECT ali_express_item.id, ali_express_item.title, ali_express_item.price, ali_express_item.stock, ali_express_product_id_id, image.ali_express_product_id_id,  image.image_link   FROM ali_express_item JOIN image ON ali_express_item.id = image.ali_express_product_id_id  WHERE ali_express_item.id = ' . $row['product_id'];
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
                            $aliImage = $it['image_link'];
                        } else {
                            $product = null;
                            $profit = null;
                            $aliImage = null;
                        }
                    }
                }
            }

            $orderArray[] = [
              'order' => $order,
              'profit' => $profit,
              'type' => $type,
              'defaultImage'   => $itemPicture,
              'ali' => $aliImage
            ];
        }

        return $orderArray;
    }

    /**
     * @param $userToken
     * @param $itemID
     * @return array
     */
    public function getImage($userToken, $itemID): array
    {
        $request = new Types\GetItemRequestType();
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $userToken;
        $request->ItemID = $itemID;
        $response = $this->services->getItem($request);
        $gallery = [];

        if (!empty($response->Item->PictureDetails->GalleryURL)) {
            $gallery[$itemID] = $response->Item->PictureDetails->GalleryURL;
        } else {
            $gallery[$itemID] = null;
        }

        return $gallery;
    }
}