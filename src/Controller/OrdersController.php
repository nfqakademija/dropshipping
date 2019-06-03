<?php

namespace App\Controller;

use App\Ebay\EbayManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrdersController extends AbstractController
{
    /**
     * @Route("dashboard/ebay/orders", name="orders")
     */
    public function index(EbayManager $ebayManager)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $entityManager = $this->getDoctrine()->getManager();
        $myOrders = $ebayManager->getOrders($userToken, $entityManager);
        $notShipped = $myOrders;
        $notShip = [];

//        dump($myOrders);

//        foreach ($notShipped as $row) {
//            if ($row->ShippedTime === null) {
//                $notShip[] = $row;
//            }
//        }
        $countNotShipped = count($notShip);

        return $this->render('orders/index.html.twig', [
            'controller_name'   => 'OrdersController',
            'order_array'       => $myOrders,
            'not_shipped'       => $notShip,
            'count_not_shipped' => $countNotShipped
        ]);
    }

    /**
     * @param EbayManager $ebayManager
     * @param $orderID
     * @return JsonResponse
     */
    public function markAsShipped(EbayManager $ebayManager, $orderID)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $response = $ebayManager->markShipped($userToken, $orderID, true);

        return new JsonResponse($response);
    }

    /**
     * @param EbayManager $ebayManager
     * @param $orderID
     * @return JsonResponse
     */
    public function unmarkShipped(EbayManager $ebayManager, $orderID)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $response = $ebayManager->markShipped($userToken, $orderID, false);

        return new JsonResponse($response);
    }

    public function leaveFeedback(EbayManager $ebayManager, $transactionID, $orderline, $itemID, $orderID, $message)
    {
        $orderArray = [
            'transaction' => $transactionID,
            'orderline' => $orderline,
            'itemID'    => $itemID,
            'orderID'   => $orderID,
            'message'   => $message
        ];
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $response = $ebayManager->feedBack($userToken, $orderArray);

        return new JsonResponse($response);
    }

    public function setTrackingNumber(EbayManager $ebayManager, $orderID, $number, $carrier)
    {
        $orderArray = [
            'orderID'           => $orderID,
            'trackingNumber'    => $number,
            'carrier'           => $carrier
        ];
    }
}
