<?php

namespace App\Service\Amazon\AmazonToEbay;

use \DTS\eBaySDK\Trading\Enums;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use App\AliExpressToEbay\EbayItem;
use App\AliExpressToEbay\EbayRequest;
use App\AliExpressToEbay\EbayService;

class AmazonToEbayManager
{
    private $productData;
    
    /**
     * @var Types\AddFixedPriceItemRequestType $ebayRequest
     */
    private $ebayRequest;

    /**
     * @var Services\TradingService $ebayService
     */
    private $ebayService;

    /**
     * @var AddedEbayItemDataSaver
     */
    private $addedEbayItemDataSaver;

    
    
    public function __construct(
        EbayRequest $ebayRequest,
        EbayService $ebayService,
        AddedEbayItemDataSaver $addedEbayItemDataSaver
    )
    {
        $this->ebayRequest = $ebayRequest->getRequest();
        $this->ebayService = $ebayService;
        $this->addedEbayItemDataSaver = $addedEbayItemDataSaver;
    }
    

    /**
     * @param array $product
     */
    public function addProductToEbay(array $product)
    {
        $this->ebayService->setService($product['shopCountry']);
        $this->ebayService = $this->ebayService->getService();

        $ebayItem = new EbayItem();
        $ebayItem->addPropertiesToItem($product);
        $item = $ebayItem->getItem();
        $this->ebayRequest->Item = $item;

        try{
            $response = $this->ebayService->addFixedPriceItem($this->ebayRequest);
        } catch (\Exception $e) {
            $e->getMessage();
        }

        $this->addedEbayItemDataSaver->saveEbayItem($product['id'], $response->ItemID, 'amazon');
      

    }

}