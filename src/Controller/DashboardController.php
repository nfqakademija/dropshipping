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
        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();

        $form = $this->createForm(ItemImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if($data['importSource'] === 1) {
                $productId = $this->getAliExpressProductId($data);
                return $this->forward('App\Controller\AliExpressController::index', ['productId' => $productId]);
            } elseif ($data['importSource'] === 2) {
                $productId = "";
            }

        }

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => $user, 'itemInputForm' => $form->createView()
        ]);
    }

    /**
     * @param array $product
     * @return int
     */
    public function  getAliExpressProductId(array $product): int
    {
        preg_match('@/(\d+)\.html@',$product['importLink'], $id);
        return (int)$id[1];
    }
}
