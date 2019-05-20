<?php


namespace App\AliExpressToEbay;


use \DTS\eBaySDK\Trading\Enums;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;

class AliExpressToEbayManager
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

    public function __construct(EbayRequest $ebayRequest, EbayService $ebayService)
    {
        $this->ebayRequest = $ebayRequest->getRequest();
        $this->ebayService = $ebayService;

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

        $response = $this->ebayService->addFixedPriceItem($this->ebayRequest);

//        if (isset($response->Errors)) {
//            foreach ($response->Errors as $error) {
//                printf(
//                    "%s: %s\n%s\n\n",
//                    $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
//                    $error->ShortMessage,
//                    $error->LongMessage
//                );
//            }
//        }
//        if ($response->Ack !== 'Failure') {
//            printf(
//                "The item was listed to the eBay Sandbox with the Item number %s\n",
//                $response->ItemID
//            );
//        }
    }
}