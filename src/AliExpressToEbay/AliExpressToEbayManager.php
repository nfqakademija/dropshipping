<?php


namespace App\AliExpressToEbay;


use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;

class AliExpressToEbayManager
{
    private $productData;

    /**
     * @var Types\AddFixedPriceItemRequestType $ebayRequest
     */
//    private $ebayRequest;

    private $catRequest;
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
     * @throws \Exception
     */
    public function addProductToEbay(array $product)
    {
        $this->ebayService->setService($product['shopCountry']);
        $this->ebayService = $this->ebayService->getService();

        $ebayItem = new EbayItem();
        $ebayItem->addPropertiesToItem($product);
        $item = $ebayItem->getItem();
        $this->ebayRequest->Item = $item;

        try {
            $response = $this->ebayService->addFixedPriceItem($this->ebayRequest);
        } catch (\Exception $e) {
            throw $e;
        }

        if (isset($response->Errors)) {
            throw new \Exception("Oops, there was a problem adding product to eBay.");
        }

        $this->addedEbayItemDataSaver->saveEbayItem($product['id'], $response->ItemID, 'aliexpress');
    }
}