<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\User;
use \Datetime;
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
     * @Route("/plan/subscribe/{id}", name="subscribe", methods={"POST"})
     */
    public function subscribe($id)
    {
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found with id '.$userId
            );
        }

        $user->setPlanId($id);

        $date = new DateTime("now");
        $user->setPlanStartTime($date);

        $expDate = clone $date;
        $expDate->modify('+ 30 days');
        $user->setPlanExpireTime($expDate);

        $entityManager->flush();

        return $this->redirectToRoute('success', [
        ]);
    }

    /**
     * @Route("/plan/success", name="success")
     */
    public function show()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();
        $planId = $this->get('security.token_storage')->getToken()->getUser()->getPlanId();

        $entityManager = $this->getDoctrine()->getManager();
        $plan = $entityManager->getRepository(Plan::class)->find($planId);

        return $this->render('plan/subscribe.html.twig', [
            'controller_name' => $user, 'plan' => $plan
        ]);
    }

}
