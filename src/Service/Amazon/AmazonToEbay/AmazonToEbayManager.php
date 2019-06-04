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
     * @var AddedFromAmazonToEbayDataSaver
     */
    private $addedFromAmazonToEbayDataSaver;

    
    
    public function __construct(
        EbayRequest $ebayRequest,
        EbayService $ebayService,
        AddedFromAmazonToEbayDataSaver $addedFromAmazonToEbayDataSaver
    )
    {
        try{
            $this->ebayRequest = $ebayRequest->getRequest();
            $this->ebayService = $ebayService;
            $this->addedFromAmazonToEbayDataSaver = $addedFromAmazonToEbayDataSaver;
        } catch (\Exception $e) {
            //$e->getMessage();
        }
    }
    

    /**
     * @param array $product
     */
    public function addProductToEbay(array $product)
    {
        try{
            
            $this->ebayService->setService($product['shopCountry']);
            $this->ebayService = $this->ebayService->getService();

            $ebayItem = new EbayItem();
            $ebayItem->addPropertiesToItem($product);
            $item = $ebayItem->getItem();
            $this->ebayRequest->Item = $item;
            
            $response = $this->ebayService->addFixedPriceItem($this->ebayRequest);
            
            
            if((isset($product['id']))&&(isset($response->ItemID))){
            
                $this->addedFromAmazonToEbayDataSaver->saveEbayItem($product['id'], $response->ItemID, 'amazon');
            }
      
            
        } catch (\Exception $e) {
            //$e->getMessage();
        }
      

    }

}