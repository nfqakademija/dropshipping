<?php

namespace App\Controller;


use App\Amazon\AmazonManager;
use App\Entity\AmazonItem;
use App\Form\AmazonItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Amazon\AmazonToEbay\AmazonToEbayManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


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
            //echo $e->getMessage();
        }
    }
    
    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAmazonItem(int $id, Request $request, EntityManagerInterface $em, AmazonToEbayManager $amazonToEbayManager)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $amazonItem = $this
            ->getDoctrine()
            ->getRepository(AmazonItem::class)
            ->findBy(['user' => $user, 'id' => $id]);

        $images = $amazonItem[0]->getImages();
        $form = $this->createForm(AmazonItemType::class, $amazonItem[0]);
        
        $form->handleRequest($request);
        
        //dump($form->isSubmitted());
        //exit();
        
        if ($form->isSubmitted() && $form->isValid()) {
        //if ($form->isSubmitted()) {
            //echo("*** form valid ***");
            //exit();
            $em->persist($amazonItem[0]);
            $em->flush();
            
            try {
                $data = $request->request->get('amazonProduct');
                //dump($data);
                //exit();

                
                
                $amazonToEbayManager->addProductToEbay($data, $amazonItem[0]);
       
                //echo "hello 545_2";
                //exit();

                //return $this->redirectToRoute('amazon');
            } catch (\Exception $e) {
                /*echo $e->getMessage();
                exit();*/
                $this->addFlash(
                'danger',
                $e->getMessage()
                //. "e->getMessage() = ".$e->getMessage()
            );
            }
            
            return $this->redirectToRoute('amazon');
        }
        
        //echo("*** 454545 ***");
        //exit();

        return $this->render('amazon/editAmazon.html.twig', [
            'item' => $amazonItem[0],
            'images' => $images,
            'form' => $form->createView(),
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
    /*public function amazonToEbay(Request $request, AmazonToEbayManager $amazonToEbayManager)
    {
        try {
            $data = $request->request->get('amazonProduct');

            $amazonToEbayManager->addProductToEbay($data);
       

            return $this->redirectToRoute('amazon');
         } catch (\Exception $e) {
            //$e->getMessage();
        }
    }*/
    
}