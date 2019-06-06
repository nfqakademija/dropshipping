<?php

namespace App\Service\Amazon\AmazonToEbay;

use \DTS\eBaySDK\Trading\Enums;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use App\Service\Amazon\AmazonToEbay\AmazonEbayItem;
use App\AliExpressToEbay\EbayRequest;
use App\AliExpressToEbay\EbayService;
use Psr\Log\LoggerInterface;

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
    
    private $logger;

    
    
    public function __construct(
        EbayRequest $ebayRequest,
        EbayService $ebayService,
        AddedFromAmazonToEbayDataSaver $addedFromAmazonToEbayDataSaver,
        LoggerInterface $logger
    )
    {
        try{
            $this->ebayRequest = $ebayRequest->getRequest();
            $this->ebayService = $ebayService;
            $this->addedFromAmazonToEbayDataSaver = $addedFromAmazonToEbayDataSaver;
            $this->logger = $logger;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    

    /**
     * @param array $product
     */
    public function addProductToEbay(array $product, $amazonItem)
    {
        
            
            $this->ebayService->setService($product['shopCountry']);
            
            $this->ebayService = $this->ebayService->getService();
           
            $ebayItem = new AmazonEbayItem();
            
            $ebayItem->addPropertiesToItem($product, $amazonItem);
            
            $item = $ebayItem->getItem();
           
            $this->ebayRequest->Item = $item;
            
            $response = $this->ebayService->addFixedPriceItem($this->ebayRequest);
            
            
          
                //dump($response->Errors->offsetGet(0)->LongMessage);
                //dump($response->Errors->offsetGet(0)->ShortMessage);

                if (isset($response->Errors)) {
                    $this->logger->error('*** response->Errors->offsetGet(0)->LongMessage ***');
                    $this->logger->error($response->Errors->offsetGet(0)->LongMessage);
                    $this->logger->error('*** response->Errors->offsetGet(0)->ShortMessage ***');
                    $this->logger->error($response->Errors->offsetGet(0)->ShortMessage);
                    throw new \Exception("Oops, there was a problem adding product to eBay.");

                }
            
            
            
                    $this->addedFromAmazonToEbayDataSaver->saveEbayItem(
                        $amazonItem->getId(), 
                        $response->ItemID, 'amazon');
           
    }

}