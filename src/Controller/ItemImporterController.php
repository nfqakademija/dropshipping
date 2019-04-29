<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ItemImportType;
use App\Entity\Item;

class ItemImporterController extends AbstractController
{
    /**
     * @Route("/item-importer", name="item_importer")
     */
    public function index()
    {
        return $this->render('item_importer/index.html.twig', [
            'controller_name' => 'ItemImporterController',
        ]);
    }
    
    /**
     * @Route("/item-importer/import", name="item_import", methods={"GET","POST"})
     */
    public function import(Request $request): Response
    {
        //$item = new Item();
        //$form = $this->createForm(ItemType::class, $item);
        $form = $this->createForm(ItemImportType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $itemsForImport=$this->getDoctrine()->getRepository('App\Entity\ItemForImport')->findAll();
            
            $randKey=rand(0, count($itemsForImport)-1);
            print_r($itemsForImport[$randKey]);
            
            $item = new Item();
            
            $item->setTitle($itemsForImport[$randKey]->getTitle());
            $item->setUser($this->getUser());
            $item->setCategory($itemsForImport[$randKey]->getCategory());
            $item->setPrice($itemsForImport[$randKey]->getPrice());
            $item->setSellPrice(0);
            $item->setQty($itemsForImport[$randKey]->getQty());
            $item->setBrand($itemsForImport[$randKey]->getBrand());
            
            $item->setDescription($itemsForImport[$randKey]->getDescription());
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();
            
            //exit();
            
            //$entityManager = $this->getDoctrine()->getManager();
            //$entityManager->persist($item);
            //$entityManager->flush();

            return $this->redirectToRoute('item_edit', array('id' => $item->getId()));
        }
        
        //return $this->redirectToRoute('item_index');

        return $this->render('item_importer/import.html.twig', [
            //'item' => $item,
            'form' => $form->createView(),
        ]);
    }
}
