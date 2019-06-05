<?php


namespace App\Service;

use App\ExternalApi\EbayAuth;
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
    }

    public function getSessionId($sandbox)
    {
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

    public function fetchUserId($sandbox, $entityManager, $userId)
    {
        $oldEbayAuth = new EbayAuth($sandbox, $entityManager, $userId);
        $fetchToken = $oldEbayAuth->fetchMyToken($_SESSION['sessionid']);

        return $fetchToken;
    }

    public function oauthUrl()
    {
        return 'https://auth.sandbox.ebay.com/oauth2/authorize?client_id=MantasPu-codezipk-SBX-555b8f5d2-321e0c95&response_type=code&redirect_uri=Mantas_Pudziavi-MantasPu-codezi-asshpnqfd&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/buy.order.readonly https://api.ebay.com/oauth/api_scope/buy.guest.order https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.marketplace.insights.readonly https://api.ebay.com/oauth/api_scope/commerce.catalog.readonly https://api.ebay.com/oauth/api_scope/buy.shopping.cart https://api.ebay.com/oauth/api_scope/buy.offer.auction https://api.ebay.com/oauth/api_scope/commerce.identity.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.email.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.phone.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.address.readonly https://api.ebay.com/oauth/api_scope/commerce.identity.name.readonly';
    }

    public function oauthToken($sandbox, $entityManager, $userId)
    {
        $ebayOauth = new EbayOauth($sandbox, $entityManager, $userId);

        $getToken = $ebayOauth->getUserToken();

        return $getToken;
    }

    public function tokenRefresh($sandbox, $entityManager, $userId)
    {
        $ebayOauth = new EbayOauth($sandbox, $entityManager, $userId);

        $refreshToken = $ebayOauth->userTokenRefresh();

        return $refreshToken;
    }




}