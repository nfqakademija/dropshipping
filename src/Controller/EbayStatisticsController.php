<?php

namespace App\Controller;

use App\Ebay\EbayAccount;
use DTS\eBaySDK\PostOrder\Types\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class EbayStatisticsController extends AbstractController
{
    /**
     * @Route("/ebay/statistics", name="ebay_statistics")
     */
    public function index(EbayAccount $ebayAccount)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $myFeedBack = $ebayAccount->getUserFeedbacks($userToken);
        $transaction = $ebayAccount->getTransactionsDetails($userToken);
        $myTransactions = $transaction->getTransactions();

        $countActiveList = count($myTransactions->ActiveList->ItemArray->Item);

        $firstDay = new \DateTime( date("Y-m-d") );
        $firstDay->modify( 'first day of previous month' );
//        echo $firstDay->format( 'Y-m-d' );


//        dump($transaction->countMonthOrders());

        $countLastMonth = array();
        $lastMonthValues = [];

        foreach($transaction->getLastMonth()->OrderArray->Order as $row => $key) {
//            dump(array_sum($key[$row]->Total));
            $lastMonthValues[] = $key->Total->value;
        }

        $lastMonth['lastMonth'] = [
            'soldItems' => count($transaction->getLastMonth()->OrderArray->Order),
            'soldValue' => array_sum($lastMonthValues)
        ];


        return $this->render('ebay_statistics/index.html.twig', [
            'controller_name' => 'EbayStatisticsController',
            'summaryData'  => [
                'active_list'           => $countActiveList,
                'total_sold'            => $myTransactions->Summary->TotalSoldCount,
                'total_sold_currency'   => $myTransactions->Summary->TotalSoldValue->currencyID,
                'total_sold_value'      => $myTransactions->Summary->TotalSoldValue->value
            ],
            'lastMonthData' => $lastMonth
        ]);
    }

    public function createMonthGraph(EbayAccount $ebayAccount)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $transaction = $ebayAccount->getTransactionsDetails($userToken);
        $orders = $transaction->countMonthOrders();

        return new JsonResponse($orders);
    }
}
