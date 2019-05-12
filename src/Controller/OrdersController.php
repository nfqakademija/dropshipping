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

//        foreach($myOrders->OrderArray->Order as $order) {
//            var_dump($order->PaidTime);
////            var_dump($order->);
//            foreach($order->ShippingAddress as $detail) {
//                var_dump($detail);
//            }
//        }
//
//        var_dump($myOrders->OrderArray->Order);

        return $this->render('orders/index.html.twig', [
            'controller_name' => 'OrdersController',
            'order_array'     => $myOrders->OrderArray->Order,
        ]);
    }
}
