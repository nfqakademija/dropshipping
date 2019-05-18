<?php

namespace App\Controller;


use App\AliExpress\AmazonManager;
//use App\Entity\AliExpressItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AmazonController extends AbstractController
{
    /**
     * @param array $data
     * @param AmazonManager $amazonManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function importProduct(array $data, AmazonManager $amazonManager)
    {
        $amazonManager->addProduct($data);

        return $this->redirectToRoute('dashboard');
    }
}