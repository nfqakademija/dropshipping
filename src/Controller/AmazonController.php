<?php

namespace App\Controller;


use App\Amazon\AmazonManager;
use App\Entity\AmazonItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Amazon\AmazonToEbay\AmazonToEbayManager;
use Symfony\Component\HttpFoundation\Request;


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
                "Could not get product details form Amazon. Please check, "
                . "if the link is correct and try again. "
                //. "e->getMessage() = ".$e->getMessage()
            );
        }

        return $this->redirectToRoute('dashboard');
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show()
    {
        try {
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $amazonItems = $this->getDoctrine()->getRepository(AmazonItem::class)->findBy(['user' => $user]);

            return $this->render('amazon/index.html.twig', [
                'controller_name' => $user->getFirstName(), 'amazonItems' => $amazonItems
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    
    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAmazonItem(int $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $amazonItem = $this
            ->getDoctrine()
            ->getRepository(AmazonItem::class)
            ->findBy(['user' => $user, 'id' => $id]);

        //$images = $amazonItem[0]->getImages()->getValues();
        $images = $amazonItem[0]->getImages();
        //dump($images->getValues());
        //exit();

        return $this->render('amazon/edit.html.twig', [
            //'controller_name' => $user->getFirstName(),
            'item' => $amazonItem[0],
            //'images' => array(),
            'images' => $images,
        ]);
    }
    
    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showDescription(int $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $amazonItem = $this
            ->getDoctrine()
            ->getRepository(AmazonItem::class)
            ->findBy(['user' => $user, 'id' => $id]);

        return $this->render('amazon/description.html.twig', [
            'controller_name' => $user->getFirstName(), 'item' => $amazonItem[0]
        ]);
    }
    
    /**
     * @param Request $request
     * @param AmazonToEbayManager $amazonToEbayManager
     */
    public function amazonToEbay(Request $request, AmazonToEbayManager $amazonToEbayManager)
    {
        try {
            $data = $request->request->get('amazonProduct');

            $amazonToEbayManager->addProductToEbay($data);
        } catch (\Exception $e) {
            $e->getMessage();
        }

        return $this->redirectToRoute('amazon');
    }
    
}