<?php

namespace App\Controller;

use App\Entity\Plan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlanController extends AbstractController
{
    /**
     * @Route("/plan", name="plan")
     */
    public function index()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();
        $plans = $this->getDoctrine()->getRepository(Plan::class)->findAll();

        return $this->render('plan/index.html.twig', [
            'controller_name' => $user, 'plans' => $plans
        ]);
    }

    /**
     * @Route("/plan/subscribe/{id}", name="subscribe")
     */
    public function subscribe($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();


        return $this->render('plan/subscribe.html.twig', [
            'controller_name' => $user, 'id' => $id
        ]);
    }
}
