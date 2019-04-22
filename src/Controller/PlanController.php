<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlanController extends AbstractController
{
    /**
     * @Route("/plan", name="plan")
     */
    public function index()
    {
        return $this->render('plan/index.html.twig', [
            'controller_name' => 'PlanController',
        ]);
    }
}
