<?php


namespace App\ExternalApi;


use App\Entity\User;
use App\Service\EbaySession;
use \DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services as TradServ;
use \DTS\eBaySDK\Trading\Types as TradTypes;

class EbayAuth
{
    private $config;

    private $entity;

    private $userId;

    /**
     * EbayAuth constructor.
     * @param $config
     * @param $entity
     * @param $userId
     */
    public function __construct($config, $entity, $userId)
    {
        $this->config = $config;
        $this->entity = $entity;
        $this->userId = $userId;
    }

    public function getSessionID()
    {
        $service = new TradServ\TradingService([
            'credentials' => $this->config['credentials'],
            'sandbox' => true,
            'siteId'      => Constants\SiteIds::US
        ]);

        $getsessionid = $service->getSessionID(
            new TradTypes\GetSessionIDRequestType([
                'RuName' => $this->config['ruName']
            ])
        );

        $_SESSION["sessionid"] = $getsessionid->SessionID;

        return $this->generateLogin($_SESSION['sessionid']);
    }

    public function generateLogin($session)
    {
        return 'https://signin.sandbox.ebay.com/ws/eBayISAPI.dll?SignIn&RuName='.$this->config['ruName'].'&SessID='.urlencode($session);
    }

    public function fetchMyToken($theID)
    {
        $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
        $requestXmlBody .= '<FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
        $requestXmlBody .= '<WarningLevel>High</WarningLevel>';
        $requestXmlBody .= "<SessionID>".$theID."</SessionID>";
        $requestXmlBody .= "<Version>1031</Version>";
        $requestXmlBody .= '</FetchTokenRequest>';
        $session = new eBaySession($this->config);
        $responseXml = $session->sendHttpRequest($requestXmlBody, 'FetchToken');

        if(stristr($responseXml, 'HTTP 404') || $responseXml == '') {
            die('<P>Error sending request');
        }

        $user = $this->entity->getRepository(User::class)->find($this->userId);

        $resp = simplexml_load_string($responseXml);

        $userToken = (string)$resp->eBayAuthToken;

        $userExpired = (string)$resp->HardExpirationTime;

        $user->setOldEbayAuth($userToken);
        $user->setOldExpiredTime($userExpired);
        $this->entity->flush();
    }

}