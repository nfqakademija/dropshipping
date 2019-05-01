<?php


namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;
use App\Service\EbaySession;


class EbayToken
{
    public $resp;
    public $token;
    public $expiration;

    public function __construct($sandbox, $theID)
    {
        $verb = 'FetchToken';

        ///Build the request Xml string
        $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
        $requestXmlBody .= '<FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
        $requestXmlBody .= "<SessionID>$theID</SessionID>";
        $requestXmlBody .= '</FetchTokenRequest>';

        //Create a new eBay session with all details pulled in from included keys.php
        $session = new eBaySession($sandbox);
        //send the request and get response
        $responseXml = $session->sendHttpRequest($requestXmlBody, 'FetchToken');

        if(stristr($responseXml, 'HTTP 404') || $responseXml == '')
            die('<P>Error sending request');

        $resp = simplexml_load_string($responseXml);

        $this->token = (string)$resp->eBayAuthToken;
        $this->expiration = $resp->HardExpirationTime;
    }

}