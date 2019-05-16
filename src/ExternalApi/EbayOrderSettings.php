<?php


namespace App\ExternalApi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;


class EbayOrderSettings
{
    public function markUnmark($config, $userToken, $orderID, $type)
    {

        $service = new Services\TradingService([
            'credentials' => $config['credentials'],
            'siteId'      => Constants\SiteIds::US,
            'sandbox' => true
        ]);

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

        $response = $service->completeSale($request);

        return $response;
    }
}