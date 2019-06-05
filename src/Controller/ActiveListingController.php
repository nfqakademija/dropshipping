<?php

namespace App\Controller;

use App\Ebay\EbayManager;
use App\Service\EbayListingFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActiveListingController extends AbstractController
{
    public function index($filter, EbayManager $ebayManager): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $myActiveItems = null;
        $countAliexpress = null;
        $countAmazon = null;

        if (!is_null($userToken)) {
            $myActiveItems = $ebayManager->mySelling($userToken, $entityManager);

            foreach ($myActiveItems as $row) {
                if ($row['type'] === "aliexpress") {
                    $countAliexpress[] = $row['type'];
                }
                if ($row['type'] === "amazon") {
                    $countAmazon[] = $row['type'];
                }
            }

        }

        $ebayFilter = $replace = str_replace('filter=', '', $filter);

        return $this->render('active_listing/index.html.twig', [
            'controller_name'   => 'ActiveListingController',
            'ebay_auth_token'   => $userToken,
            'activeListing'     => $myActiveItems,
            'filter'            => $ebayFilter,
            'product_counter'   => [
                'aliexpress'    => (!is_null($countAliexpress)) ? count($countAliexpress) : 0,
                'amazon'        => (!is_null($countAmazon)) ? count($countAmazon) : 0,
            ]
        ]);
    }
}
