<?php


namespace App\Service;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Service\EbaySession;
use App\Service\EbayToken;
use Symfony\Component\HttpFoundation\Request;
use \DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services as TradServ;
use \DTS\eBaySDK\Trading\Types as TradTypes;
use App\ExternalApi\EbayOauth;


class EbayClient
{
    /**
     * Get API credentials
     * @param $sandbox [array]
     */

    private $ruName;
    public $sessionId;

    public $resp;
    public $token;
    public $expiration;

    public $userId;

    public function __construct($sandbox)
    {
        $this->ruName = $sandbox[3]['ruName'];
        $this->sessionId = $this->getSessionId($sandbox);
//        $this->userId = $userId;
    }

    public function getSessionId($sandbox)
    {
/*        $requestBody1 = '<?xml version="1.0" encoding="utf-8" ?>';*/
//        $requestBody1 .= '<GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
//        $requestBody1 .= "<RequesterCredentials>";
//        $requestBody1 .= "<eBayAuthToken>AgAAAA**AQAAAA**aAAAAA**Bge+XA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4aiCJeFpQSdj6x9nY+seQ**r/IEAA**AAMAAA**zqrDMlnBLwIby9V01+9IHpJuaJ6cRm9tgPyujCF4lcfROs5aPqTdMudrHXaCm1irJGTnaRsYwMWQ1/B+iEFykCh7AfUVF/C9szV8WVqdm0kA4AcsopICBxw9P3/esZxLIgq+qR5Olil86fNW+ITWualPxGLl3YjW7XkACzRlemJ/NibzrFzkBAIyKbJSyhZZwUeOApXEFnB6pbT15/dVLC+BKhdPYpC4h9n1u3MGrhxTAyzxFGPr2hLMpUA0KSyIx1m74Ipumn4L6JZbpvL9sLQUNIVDBd9NUqCCcgO2xGcwKPNXYEg18wJJXwZijFIRJZ3QI6ov4XvC47wDBozj7Bfnl4fU/WV+K52yaGjIU6VB3k+XhDxsSEsVWCUt84FymlNF3avwpAM4ELGnheEY7cS6/eca/b15TMoqjmkaF9Y3eC9w1JTzEWkOK1Y/h7Qf6+RK41xSRZWBMvZGqjLxhQTzSSX0FqzCz+r+PLd8XfFIgrqDcvoc5uHZMqUJ5o59bK6/QxLXPrgrmjZbi6UcsRvhbQw3TLGw8yVPh6KweNI5m+d7L/aY3OkYIyKZAYxiMC9lXJNQJtNZcksEbyFOEg5fj0/BaErzixKBnXMBgwI3czUQEjgPqNbs893k7yz05+YTvfYxY4/w0jCDrcUjnSRf1Bk3Dj1mIAmQLktyJxdDmbeeXrCDbmxwk6SFskSLYmGpVdyRgiDrdPbnm3zhRrBE85+ahuEo9YvHGn5Jo2XvBkPdj3mJFxLEZ3tjFvY9</eBayAuthToken>";
//        $requestBody1 .= "</RequesterCredentials>";
//        $requestBody1 .= "<Version>1031</Version>";
//        $requestBody1 .= "<RuName>Mantas_Pudziavi-MantasPu-codezi-asshpnqfd</RuName>";
//        $requestBody1 .= '</GetSessionIDRequest>';
//
//        $sessN = new eBaySession($sandbox);
//
//        $responseBody1 = $sessN->sendHttpRequest($requestBody1, 'GetSessionID');
//
//        $resp1 = simplexml_load_string($responseBody1);
//
//        $_SESSION['ebSession'] = (string)$resp1->SessionID;
//
//        $sesId = urlencode($_SESSION['ebSession']);

        $service = new TradServ\TradingService([
            'credentials' => $sandbox[0]['credentials'],
            'sandbox' => true,
            'siteId'      => Constants\SiteIds::US
        ]);

        $getsessionid = $service->getSessionID(
            new TradTypes\GetSessionIDRequestType([
                'RuName' => 'Mantas_Pudziavi-MantasPu-codezi-asshpnqfd'
            ])
        );
        $_SESSION["sessionid"] = $getsessionid->SessionID;

        return $this->loginToApplicationUrl();
    }

    public function loginToApplicationUrl()
    {
        return 'https://signin.sandbox.ebay.com/ws/eBayISAPI.dll?SignIn&RuName=Mantas_Pudziavi-MantasPu-codezi-asshpnqfd&SessID='.urlencode($_SESSION['sessionid']);
    }

    public function oauthUrl()
    {
        return 'https://auth.sandbox.ebay.com/oauth2/authorize?client_id=MantasPu-codezipk-SBX-555b8f5d2-321e0c95&response_type=code&redirect_uri=Mantas_Pudziavi-MantasPu-codezi-asshpnqfd&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/buy.order.readonly https://api.ebay.com/oauth/api_scope/buy.guest.order https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.marketplace.insights.readonly https://api.ebay.com/oauth/api_scope/commerce.catalog.readonly https://api.ebay.com/oauth/api_scope/buy.shopping.cart https://api.ebay.com/oauth/api_scope/buy.offer.auction https://api.ebay.com/oauth/api_scope/commerce.identity.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.email.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.phone.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.address.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.name.readonly';
    }

    public function getUserToken($sandbox)
    {
//        $service = new TradServ\TradingService([
//            'credentials' => $sandbox[0]['credentials'],
//            'sandbox' => true,
//            'siteId'      => Constants\SiteIds::US
//        ]);
//
//        $getsessionid = $service->getSessionID(
//            new TradTypes\GetSessionIDRequestType([
//                'RuName' => 'Mantas_Pudziavi-MantasPu-codezi-bovmcywta'
//            ])
//        );
//        $_SESSION["sessionid"] = $getsessionid->SessionID;
//
        $theID = $_SESSION["sessionid"];

        $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
        $requestXmlBody .= '<FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
        $requestXmlBody .= '<WarningLevel>High</WarningLevel>';
        $requestXmlBody .= "<SessionID>".$theID."</SessionID>";
        $requestXmlBody .= "<Version>1031</Version>";
        $requestXmlBody .= '</FetchTokenRequest>';
        $session = new eBaySession($sandbox);
        //send the request and get response
        $responseXml = $session->sendHttpRequest($requestXmlBody, 'FetchToken');

        if(stristr($responseXml, 'HTTP 404') || $responseXml == '')
            die('<P>Error sending request');

        $resp = simplexml_load_string($responseXml);
//
//
        return $resp;

    }

    public function oauthToken($sandbox, $entityManager, $userId)
    {
        $ebayOauth = new EbayOauth($sandbox, $entityManager, $userId);

        return $ebayOauth;
    }




}