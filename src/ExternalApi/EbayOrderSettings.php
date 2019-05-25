<?php


namespace App\ExternalApi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\MerchantData\Types\SetShipmentTrackingInfoRequestType;
use DTS\eBaySDK\Trading\Enums\CommentTypeCodeType;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;


class EbayOrderSettings
{

    /**
     * @var Services\TradingService
     */
    public $services;

    public function __construct($services)
    {
        $this->services = $services;
    }

    /**
     * @param $userToken
     * @param $orderID
     * @param $type
     * @return Types\CompleteSaleResponseType
     */
    public function markUnmark($userToken, $orderID, $type)
    {
        $ship = new Types\ShipmentTrackingDetailsType();
        $ship->ShipmentTrackingNumber = 'Nera';
        $ship->ShippingCarrierUsed = '44444';
        $shipDetails = array($ship);

        $request = new Types\CompleteSaleRequestType();
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $userToken;
        $request->OrderID = $orderID;
        $request->Shipped = $type;

        $request->MessageID = "Sended";

        $response = $this->services->completeSale($request);

        return $response;
    }

    public function insertFeedback($userToken, $transaction)
    {
        $request = new Types\LeaveFeedbackRequestType();
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $userToken;
        $request->CommentText = 'Thanks for buying!';
        $request->CommentType = CommentTypeCodeType::C_POSITIVE;
        $request->ItemID = '110395188368';
//        $request->OrderLineItemID = '110395188368-29005368001';
        $request->TargetUser = 'testuser_nfq';
        $request->TransactionID = '29004653001';
        $response = $this->services->leaveFeedback($request);

        return $response;
    }

    public function addTrackingNumber($userToken, $orderArray)
    {
        $ship = new Types\ShipmentTrackingDetailsType();
        $ship->ShipmentTrackingNumber = $number;
        $ship->ShippingCarrierUsed = $carrier;
        $shipDetails = array($ship);
        $request = new Types\CompleteSaleRequestType();
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $userToken;
        $request->OrderID = $orderID;
        $request->Shipment = new Types\ShipmentType();
        $request->Shipment->ShipmentTrackingDetails = $shipDetails;
        $request->Shipped = true;

        $response = $this->services->completeSale($request);

        return $response;
    }
}