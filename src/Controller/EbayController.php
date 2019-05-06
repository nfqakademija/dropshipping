<?php

namespace App\Controller;

use App\Ebay\EbayManager;
use App\Entity\User;
use App\ExternalApi\EbayMySelling;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EbayClient;

class EbayController extends AbstractController
{
    /**
     * @Route("/dashboard/ebay", name="ebay")
     */
    public function index(EbayManager $ebayManager)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();

        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();

        $ebayClient = new EbayClient($this->getParameter('sandbox'), $userToken);

        $myItems = $ebayManager->mySelling($userToken);

        $ebayLoginUrl = $ebayClient->getSessionId($this->getParameter('sandbox'));

        return $this->render('ebay/index.html.twig', [
            'controller_name' => $user,
            'ebay_login_url'    => $ebayLoginUrl,
            'ebay_oauth_token'  => $userToken,
            'my_ebay_items'     => $myItems
        ]);
    }

    /**
     * @Route("/dashboard/ebay/oauth", name="oauth")
     */
//    public function authenticate()
//    {
//
//        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();
//
//        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
//
//        $entityManager = $this->getDoctrine()->getManager();
//
//        $ebayClient = new EbayClient($this->getParameter('sandbox'));
////
////        $ebayToken = $ebayClient->oauthToken($this->getParameter('sandbox'), $entityManager, $userId);
//
//        $ebayClient->fetchUserId($this->getParameter('sandbox'), $entityManager, $userId);
//
//
//        return $this->render('ebay/oauth.html.twig', [
//            'controller_name' => $user
////            'ebay_oauth_response'   => $ebayToken
//        ]);
//    }

    /**
     * @Route("/dashboard/ebay/oauth", name="oauth")
     */
    public function ebayAuthenticate(EbayManager $ebayManager)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();
        $entityManager = $this->getDoctrine()->getManager();
        $userID = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $token = $ebayManager->getToken($entityManager, $userID);

        var_dump($token);

        return $this->render('ebay/oauth.html.twig', [
            'controller_name' => $user
        ]);

    }


    /**
     * @Route("/dashboard/ebay/redirect-auth", name="redirect-auth")
     */

    public function redirectAuth()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();

        $ebayClient = new EbayClient($this->getParameter('sandbox'));
//        $ebayLoginUrl = $ebayClient->getSessionId($this->getParameter('sandbox'));
        $ebayLoginUrl = $ebayClient->oauthUrl();

        return $this->redirect($ebayLoginUrl);
    }

    /**
     * @Route("/dashboard/ebay/redirect", name="redirect")
     */

    public function redirectOld(EbayManager $ebayManager)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();
        $userID = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $ebayClient = new EbayClient($this->getParameter('sandbox'));

        $createAuthUrl = $ebayManager->getSessionLogin($entityManager, $userID);

        return $this->redirect($createAuthUrl);
    }

    /**
     * @Route("/dashboard/ebay/refresh-token", name="refresh-token")
     */

    public function tokenRefresh()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();

        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $entityManager = $this->getDoctrine()->getManager();

        $ebayClient = new EbayClient($this->getParameter('sandbox'));
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOauthToken();

        $token = $ebayClient->tokenRefresh($this->getParameter('sandbox'), $entityManager, $userId);

        var_dump($userToken);

        if($userToken == $token) {
            var_dump('yes');
        }

        return $this->render('ebay/oauth.html.twig', [
            'controller_name' => 'ebay-controller'
        ]);
    }

}
