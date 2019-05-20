<?php


namespace App\AliExpressToEbay;

use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;
use \DTS\eBaySDK\Trading\Services;

class EbayItem
{
    /**
     * @var Types\ItemType $item
     */
    private $item;

    public function getItem()
    {
       return $this->item;
    }

    public function addPropertiesToItem(array $data)
    {
        $this->item = new Types\ItemType();
        $this->item->ListingType = Enums\ListingTypeCodeType::C_FIXED_PRICE_ITEM;

        $this->item->Quantity = (int) $data['stock'];
        $this->item->ListingDuration = Enums\ListingDurationCodeType::C_GTC;
        $this->item->StartPrice = new Types\AmountType(['value' => (double) $data['price']]);


        $this->item->Title = $data['title'];
        $this->item->Description = $data['description'];

        $this->item->PictureDetails = new Types\PictureDetailsType();
        $this->item->PictureDetails->GalleryType = Enums\GalleryTypeCodeType::C_GALLERY;
        $this->item->PictureDetails->PictureURL = $data['image'];

        $this->item->ConditionID = 1000;
        $this->item->PaymentMethods = [
            'PayPal'
        ];
        $this->item->PayPalEmailAddress = 'example@example.com';
        $this->item->DispatchTimeMax = 1;
        $this->addCurrency($data);
        $this->addCategory();
        $this->addLocation();
        $this->addShipping();
    }

    public function addShipping()
    {
        $this->item->ShippingDetails = new Types\ShippingDetailsType();
        $this->item->ShippingDetails->ShippingType = Enums\ShippingTypeCodeType::C_FLAT;

        $shippingService = new Types\ShippingServiceOptionsType();
        $shippingService->ShippingServicePriority = 1;
        $shippingService->ShippingService = 'DE_DeutschePostBrief';
        $shippingService->ShippingServiceCost = new Types\AmountType(['value' => 2.00]);
        $shippingService->ShippingServiceAdditionalCost = new Types\AmountType(['value' => 1.00]);
        $this->item->ShippingDetails->ShippingServiceOptions[] = $shippingService;

    }

    public function addLocation()
    {
        //ToDo add location from user settings
        $this->item->Country = "US";
        $this->item->Location = "New City";
        $this->item->PostalCode = "10956";
    }

    public function addCategory()
    {
        //ToDo add category suggestion or search list for categories
        $this->item->PrimaryCategory = new Types\CategoryType();
        $this->item->PrimaryCategory->CategoryID = '29792';
    }

    public function addCurrency(array $data)
    {
        if($data['shopCountry'] == "DE") {
            $this->item->Currency = "EUR";
        } elseif ($data['shopCountry'] == "GB") {
            $this->item->Currency = "GBP";
        }
    }
}