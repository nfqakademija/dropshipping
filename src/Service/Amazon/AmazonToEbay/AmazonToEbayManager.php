<?php

namespace App\Service\Amazon\AmazonToEbay;

use \DTS\eBaySDK\Trading\Enums;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use App\Service\Amazon\AmazonToEbay\AmazonEbayItem;
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
            $e->getMessage();
        }
    }
    

    /**
     * @param array $product
     */
    public function addProductToEbay(array $product, $amazonItem)
    {
        try{
            
            //echo "hello 5454_1";
            //exit();
            
            
            $this->ebayService->setService($product['shopCountry']);
            $this->ebayService = $this->ebayService->getService();

            $ebayItem = new AmazonEbayItem();
            $ebayItem->addPropertiesToItem($product, $amazonItem);
            $item = $ebayItem->getItem();
            $this->ebayRequest->Item = $item;
            
            $response = $this->ebayService->addFixedPriceItem($this->ebayRequest);
            
            
            //if((isset($product['id']))&&(isset($response->ItemID))){
            if((isset($item))&&(isset($response->ItemID))){
            
                $this->addedFromAmazonToEbayDataSaver->saveEbayItem($item->getId(), $response->ItemID, 'amazon');
            }else{
                //echo "hello 4587458_1";
                //exit();
            }
      
            
        } catch (\Exception $e) {
            $e->getMessage();
        }
      

    }

}