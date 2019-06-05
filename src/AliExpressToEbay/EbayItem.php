<?php


namespace App\AliExpressToEbay;

use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;

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

        $this->item->Quantity = (int)$data['stock'];
        $this->item->ListingDuration = Enums\ListingDurationCodeType::C_GTC;
        $this->item->StartPrice = new Types\AmountType(['value' => (double)$data['price']]);


        $this->item->Title = $data['title'];
        $this->item->Description = $this->correctLinksInDescription($data);

        $this->item->PictureDetails = new Types\PictureDetailsType();
        $this->item->PictureDetails->GalleryType = Enums\GalleryTypeCodeType::C_GALLERY;
        $this->item->PictureDetails->PictureURL = $data['image'];

        $this->item->ConditionID = 1000;
        $this->item->PaymentMethods = [
            'PayPal'
        ];
        $this->item->PayPalEmailAddress = 'example@example.com';
        $this->item->DispatchTimeMax = 1;
        $this->item->PrimaryCategory = new Types\CategoryType();
        $this->item->PrimaryCategory->CategoryID = $data['category'];

        $this->addCurrency($data);
        $this->addLocation();
        $this->addShipping($data);
    }

    public function addShipping(array $data)
    {
        $this->item->ShippingDetails = new Types\ShippingDetailsType();
        $this->item->ShippingDetails->ShippingType = Enums\ShippingTypeCodeType::C_FLAT;

        $shippingService = new Types\ShippingServiceOptionsType();
        $shippingService->ShippingServicePriority = 1;
        if ($data['shopCountry'] == "DE") {
            $shippingService->ShippingService = 'DE_DeutschePostBrief';
        } elseif ($data['shopCountry'] == "GB") {
            $shippingService->ShippingService = 'UK_RoyalMailFirstClassStandard';
        }

        $shippingService->ShippingServiceCost = new Types\AmountType(['value' => 2.00]);
        $shippingService->ShippingServiceAdditionalCost = new Types\AmountType(['value' => 1.00]);
        $this->item->ShippingDetails->ShippingServiceOptions[] = $shippingService;

    }

    public function addLocation()
    {
        $this->item->Country = "US";
        $this->item->Location = "New City";
        $this->item->PostalCode = "10956";
    }

    public function addCurrency(array $data)
    {
        if ($data['shopCountry'] == "DE") {
            $this->item->Currency = "EUR";
        } elseif ($data['shopCountry'] == "GB") {
            $this->item->Currency = "GBP";
        }
    }

    public function correctLinksInDescription(array $data)
    {
        $correct = preg_replace("/http:/", "https:", $data['description']);
        return $correct;
    }
}