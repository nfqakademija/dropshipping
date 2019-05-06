<?php


namespace App\Service;


class EbaySession
{
    public $config = [
        'siteId' => '0',
        'sandbox' => [
            'credentials' => [
                'devId'     => '',
                'appId'     => '',
                'certId'    => '',
            ],
            'authToken'         => '',
            'oauthUserToken'    => '',
            'ruName'            => ''
        ]
    ];

    public $serverUrl;
    public $shoppingUrl;
    public $findingURL;
    public $compatLevel;

    public function __construct($sandbox)
    {
        $this->config['credentials']      = $sandbox['credentials'];
        $this->config['authToken']        = $sandbox['authToken'];
        $this->config['oauthUserToken']   = $sandbox['oauthUserToken'];
        $this->config['ruName']           = $sandbox['ruName'];

        $this->serverUrl = 'https://api.sandbox.ebay.com/ws/api.dll';
        $this->shoppingURL = 'http://open.api.sandbox.ebay.com/shopping';
        $this->findingURL= 'http://svcs.sandbox.ebay.com/services/search/FindingService/v1';
        $this->compatLevel = '1031';
    }

    public function sendHttpRequest($requestBody, $verb)
    {
        //build eBay headers using variables passed via constructor
        $headers = $this->buildEbayHeaders($verb);

        //initialise a CURL session
        $connection = curl_init();
        curl_setopt($connection, CURLOPT_URL, $this->serverUrl);

        //stop CURL from verifying the peer's certificate
        curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);

        //set the headers using the array of headers
        curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);

        //set method as POST
        curl_setopt($connection, CURLOPT_POST, 1);

        //set the XML body of the request
        curl_setopt($connection, CURLOPT_POSTFIELDS, $requestBody);

        //set it to return the transfer as a string from curl_exec
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);

        //Send the Request
        $response = curl_exec($connection);

        //print $response;

        //close the connection
        curl_close($connection);

        //return the response
        return $response;

    }

    private function buildEbayHeaders($verb)
    {
        $headers = array (
            //Regulates versioning of the XML interface for the API
            'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $this->compatLevel,

            //set the keys
            'X-EBAY-API-DEV-NAME: ' . $this->config['credentials']['devId'],
            'X-EBAY-API-APP-NAME: ' . $this->config['credentials']['appId'],
            'X-EBAY-API-CERT-NAME: ' . $this->config['credentials']['certId'],

            //the name of the call we are requesting
            'X-EBAY-API-CALL-NAME: ' . $verb,

            //SiteID must also be set in the Request's XML
            //SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
            //SiteID Indicates the eBay site to associate the call with
            'X-EBAY-API-SITEID: ' . $this->config['siteId'],
        );

        return $headers;
    }
}