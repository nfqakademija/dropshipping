<?php

namespace App\Controller;

use App\Form\ItemImportType;
use App\Validator\LinkInputValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(Request $request, LinkInputValidator $validator)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();

        $form = $this->createForm(ItemImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && count($validator->validate($request)) === 0) {
            $data = $form->getData();

            if ($data['importSource'] === 1) {
                return $this->forward('App\Controller\AliExpressController::index', ['data' => $data]);
            } elseif ($data['importSource'] === 2) {
                return $this->forward('App\Controller\AmazonController::importProduct', ['data' => $data]);
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => $user,
            'itemInputForm' => $form->createView(),
            'errorMessages' => $validator->validate($request)
        ]);
    }
}
