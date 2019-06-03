<?php

namespace App\Controller;

use App\Ebay\EbayManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActiveListingController extends AbstractController
{
    /**
     * @Route("/dashboard/listing", name="listing")
     */
    public function index(EbayManager $ebayManager)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $myActiveItems = $ebayManager->mySelling($userToken, $entityManager);

        dump($myActiveItems);

        return $this->render('active_listing/index.html.twig', [
            'controller_name' => 'ActiveListingController',
            'ebay_auth_token'  => $userToken,
            'activeListing'        => $myActiveItems
        ]);
    }
}
