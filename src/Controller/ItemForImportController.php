<?php

namespace App\Controller;

use App\Entity\ItemForImport;
use App\Form\ItemForImportType;
use App\Repository\ItemForImportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/item/for/import")
 */
class ItemForImportController extends AbstractController
{
    /**
     * @Route("/", name="item_for_import_index", methods={"GET"})
     */
    public function index(ItemForImportRepository $itemForImportRepository): Response
    {
        return $this->render('item_for_import/index.html.twig', [
            'item_for_imports' => $itemForImportRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="item_for_import_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $itemForImport = new ItemForImport();
        $form = $this->createForm(ItemForImportType::class, $itemForImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($itemForImport);
            $entityManager->flush();

            return $this->redirectToRoute('item_for_import_index');
        }

        return $this->render('item_for_import/new.html.twig', [
            'item_for_import' => $itemForImport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_for_import_show", methods={"GET"})
     */
    public function show(ItemForImport $itemForImport): Response
    {
        return $this->render('item_for_import/show.html.twig', [
            'item_for_import' => $itemForImport,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="item_for_import_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ItemForImport $itemForImport): Response
    {
        $form = $this->createForm(ItemForImportType::class, $itemForImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_for_import_index', [
                'id' => $itemForImport->getId(),
            ]);
        }

        return $this->render('item_for_import/edit.html.twig', [
            'item_for_import' => $itemForImport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_for_import_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ItemForImport $itemForImport): Response
    {
        if ($this->isCsrfTokenValid('delete'.$itemForImport->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($itemForImport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_for_import_index');
    }
}
