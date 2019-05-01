<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EbayClient;

class EbayController extends AbstractController
{
    /**
     * @Route("/dashboard/ebay", name="ebay")
     */
    public function index()
    {
        $conf = $this->getParameter('sandbox')[3];
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOauthToken();

//        $checkToken = $_SESSION['userOauthToken'];
//
            $ebayClient = new EbayClient($this->getParameter('sandbox'), $userId);

            $ebayLoginUrl = $ebayClient->getSessionId($this->getParameter('sandbox'));

        return $this->render('ebay/index.html.twig', [
            'controller_name' => $user,
            'ebay_login_url'    => $ebayLoginUrl,
            'ebay_oauth_token'  => $userToken
        ]);
    }

    /**
     * @Route("/dashboard/ebay/oauth", name="oauth")
     */

    public function authenticate()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();

        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $entityManager = $this->getDoctrine()->getManager();

        $ebayClient = new EbayClient($this->getParameter('sandbox'));

        $ebayToken = $ebayClient->oauthToken($this->getParameter('sandbox'), $entityManager, $userId);


        return $this->render('ebay/oauth.html.twig', [
            'controller_name' => $user,
            'ebay_oauth_response'   => $ebayToken
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

}
