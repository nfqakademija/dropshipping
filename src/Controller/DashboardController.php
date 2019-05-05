<?php

namespace App\Controller;

use App\Form\ItemImportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;


class DashboardController extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(Request $request)
    {

        $form = $this->createForm(ItemImportType::class);
        $form->handleRequest($request);

        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => $user, 'itemInputForm' => $form->createView()
        ]);
    }
}
