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
        $myItems = $ebayManager->mySelling($userToken);

        return $this->render('ebay/index.html.twig', [
            'controller_name' => $user,
            'ebay_auth_token'  => $userToken,
            'my_ebay_items'     => $myItems
        ]);
    }

    /**
     * @Route("/dashboard/ebay/oauth", name="oauth")
     */
    public function ebayAuthenticate(EbayManager $ebayManager)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();
        $entityManager = $this->getDoctrine()->getManager();
        $userID = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $ebayManager->getToken($entityManager, $userID);

        return $this->render('ebay/oauth.html.twig', [
            'controller_name' => $user
        ]);

    }

    /**
     * @Route("/dashboard/ebay/redirect", name="redirect")
     */

    public function redirectToLogin(EbayManager $ebayManager)
    {
        $userID = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $createAuthUrl = $ebayManager->getSessionLogin($entityManager, $userID);

        return $this->redirect($createAuthUrl);
    }

    public function show(EbayManager $ebayManager, $id)
    {

        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $entityManager = $this->getDoctrine()->getManager();
        $item = $ebayManager->getItem($userToken, $id);

        return $this->render('ebay/show.html.twig', [
            'controller_name' => 'ebay_item_show',
            'item'  => $item
        ]);
    }

    /**
     * @Route("/dashboard/ebay/logout-ebay", name="logout-ebay")
     */
    public function logoutFromEbay() {

        $entityManager = $this->getDoctrine()->getManager();
        $userID = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $updateToken = $entityManager->getRepository(User::class)->find($userID);
        $updateToken->setOldEbayAuth(null);
        $updateToken->setOldExpiredTime(null);
        $entityManager->flush();

        return $this->redirectToRoute('profile');
    }

}
