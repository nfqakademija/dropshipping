<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;


class DashboardController extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => $user,
        ]);
    }
}
