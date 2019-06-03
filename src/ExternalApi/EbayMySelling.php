<?php


namespace App\ExternalApi;

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;

class EbayMySelling
{
    /**
     * @var Services\TradingService
     */
    public $services;

    public $entity;

    /**
     * EbayOrders constructor.
     * @param $services
     */
    public function __construct($services, $entityManager)
    {
        $this->services = $services;
        $this->entity = $entityManager;
    }

    /**
     * @param $token
     * @return array
     */
    public function getMyItems($token)
    {
        $request = new Types\GetMyeBaySellingRequestType();
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $token;
        $request->ActiveList = new Types\ItemListCustomizationType();
        $request->ActiveList->Include = true;
        $request->ActiveList->Pagination = new Types\PaginationType();
        $request->ActiveList->Pagination->EntriesPerPage = 15;
        $request->ActiveList->Sort = Enums\ItemSortTypeCodeType::C_CURRENT_PRICE_DESCENDING;
        $pageNum = 1;
        $request->ActiveList->Pagination->PageNumber = $pageNum;
        $response = $this->services->getMyeBaySelling($request);
        $database = $this->entity->getConnection();

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
            $productArray = [];
            $itemsData = [];
            $uploadProduct = [];

            foreach ($response->ActiveList->ItemArray->Item as $item => $key) {
                $sql = 'SELECT * FROM ebay_item WHERE ebay_id = '.$key->ItemID;
                $stmt = $database->prepare($sql);
                $stmt->execute();
                $itemsData = $stmt->fetchAll();
                $type = null;
                $product = [];

                foreach ($itemsData as $row) {
                    if ($row['ebay_id'] === $key->ItemID) {
                        $aliSql = 'SELECT * FROM ali_express_item WHERE id = '.$row['product_id'].' LIMIT 1';
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
                        } else {
                            $product = null;
                        }
                    }
                }

                    $productArray[] = [
                    'ebayProduct' => $key,
                    'activeList'   => (!empty($itemsData) ? $itemsData : 0),
                    'product' => (!empty($product) ? $product : 0),
                    'type' => $type
                    ];
            }

            return $productArray;
        }
    }

    /**
     * @param $config
     * @param $userToken
     * @param $itemID
     * @return Types\GetItemResponseType
     */
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

        return $response;
    }
}