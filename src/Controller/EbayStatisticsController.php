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
     * @Route("/dashboard/statistics", name="ebay_statistics")
     */
    public function index(EbayAccount $ebayAccount)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $entityManager = $this->getDoctrine()->getManager();
        $myFeedBack = $ebayAccount->getUserFeedbacks($userToken);
        $transaction = $ebayAccount->getTransactionsDetails($userToken);
        $myTransactions = $transaction->getTransactions();
        $countActiveList = count($myTransactions->ActiveList->ItemArray->Item);
        $soldToBonus = $transaction->getMonthSalesBonus();

        return $this->render('ebay_statistics/index.html.twig', [
            'controller_name' => 'EbayStatisticsController',
            'summaryData' => [
                'active_list' => $countActiveList,
                'total_sold' => $myTransactions->Summary->TotalSoldCount,
                'total_sold_currency' => $myTransactions->Summary->TotalSoldValue->currencyID,
                'total_sold_value' => $myTransactions->Summary->TotalSoldValue->value
            ],
            'soldToBonus' => $soldToBonus
        ]);
    }

    /**
     * @param EbayAccount $ebayAccount
     * @return JsonResponse
     * @throws \Exception
     */
    public function showMonthGraph(EbayAccount $ebayAccount)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $transaction = $ebayAccount->getTransactionsDetails($userToken);
        $orders = $transaction->countMonthOrders();

        return new JsonResponse($orders);
    }

    public function showMonthGraphProfit(EbayAccount $ebayAccount)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $entityManager = $this->getDoctrine()->getManager();
        $transaction = $ebayAccount->getTransactionsDetails($userToken);
        $profit = $transaction->countMonthProfit($entityManager);

        return new JsonResponse($profit);
    }
}
