<?php

namespace App\Controller;


use App\Amazon\AmazonManager;
use App\Entity\AmazonItem;
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
        try {
            $amazonManager->addProduct($data);
            $this->addFlash('success', 'Amazon product imported succesfully!');
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                "Could not get product details form Amazon. Please check, if the link is correct and try again."
            );
        }

        return $this->redirectToRoute('dashboard');
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $amazonItems = $this->getDoctrine()->getRepository(AmazonItem::class)->findBy(['user' => $user]);

        return $this->render('amazon/index.html.twig', [
            'controller_name' => $user->getFirstName(), 'amazonItems' => $amazonItems
        ]);
    }
    
}