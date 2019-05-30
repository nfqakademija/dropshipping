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
        $amazonManager->addProduct($data);

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
    
}