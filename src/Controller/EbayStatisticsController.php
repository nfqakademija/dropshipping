<?php

namespace App\Controller;

use App\Ebay\EbayAccount;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EbayStatisticsController extends AbstractController
{
    /**
     * @Route("/ebay/statistics", name="ebay_statistics")
     */
    public function index(EbayAccount $ebayAccount)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $myFeedBack = $ebayAccount->getUserFeedbacks($userToken);
        $myTransactions = $ebayAccount->getSellerTransactions($userToken);

        dump($myTransactions);

        $countActiveList = count($myTransactions->ActiveList->ItemArray->Item);

        return $this->render('ebay_statistics/index.html.twig', [
            'controller_name' => 'EbayStatisticsController',
            'summaryData'  => [
                'active_list'           => $countActiveList,
                'total_sold'            => $myTransactions->Summary->TotalSoldCount,
                'total_sold_currency'   => $myTransactions->Summary->TotalSoldValue->currencyID,
                'total_sold_value'      => $myTransactions->Summary->TotalSoldValue->value
            ]
        ]);
    }
}
