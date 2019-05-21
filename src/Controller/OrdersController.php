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
        $myOrders = $ebayManager->getOrders($userToken);
        $notShipped = $myOrders;
        $notShip = [];

        foreach($notShipped->OrderArray->Order as $row) {

            if ($row->ShippedTime === null) {
                $notShip[] = $row;
            }

        }

        $countNotShipped = count($notShip);

        return $this->render('orders/index.html.twig', [
            'controller_name'   => 'OrdersController',
            'order_array'       => $myOrders->OrderArray->Order,
            'not_shipped'       => $notShip,
            'count_not_shipped' => $countNotShipped
        ]);
    }

    public function markAsShipped(EbayManager $ebayManager, $orderID)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $response = $ebayManager->markShipped($userToken, $orderID, true);

        return new JsonResponse($response);
    }

    public function unmarkShipped(EbayManager $ebayManager, $orderID)
    {
        $userToken = $this->get('security.token_storage')->getToken()->getUser()->getOldEbayAuth();
        $response = $ebayManager->markShipped($userToken, $orderID, false);

        return new JsonResponse($response);
    }
}
