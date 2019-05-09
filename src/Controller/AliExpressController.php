<?php

namespace App\Controller;


use App\AliExpress\AliExpressManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AliExpressController extends AbstractController
{
    /**
     * @Route("/ali/express", name="ali_express")
     * @param array $product
     * @param AliExpressManager $aliExpressManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(array $data, AliExpressManager $aliExpressManager)
    {
        $aliExpressManager->addProduct($data);

        return $this->redirectToRoute('dashboard');
    }
}
