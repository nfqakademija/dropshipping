<?php

namespace App\Controller;


use App\AliExpress\AliExpressManager;
use App\Entity\AliExpressItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class AliExpressController extends AbstractController
{
    /**
     * @param array $data
     * @param AliExpressManager $aliExpressManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index(array $data, AliExpressManager $aliExpressManager)
    {
        $aliExpressManager->addProduct($data);

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $aliExpressItems = $this->getDoctrine()->getRepository(AliExpressItem::class)->findBy(['user' => $user]);

        return $this->render('ali_express/index.html.twig', [
            'controller_name' => $user->getFirstName(), 'aliExpressItems' => $aliExpressItems
        ]);
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showDescription(int $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $aliExpressItem = $this
            ->getDoctrine()
            ->getRepository(AliExpressItem::class)
            ->findBy(['user' => $user, 'id' => $id]);

        return $this->render('ali_express/description.html.twig', [
            'controller_name' => $user->getFirstName(), 'description' => $aliExpressItem[0]->getDescription()
        ]);
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAliExpressItem(int $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $aliExpressItem = $this
            ->getDoctrine()
            ->getRepository(AliExpressItem::class)
            ->findBy(['user' => $user, 'id' => $id]);

        $images = $aliExpressItem[0]->getImages()->getValues();

        return $this->render('ali_express/show.html.twig', [
            'controller_name' => $user->getFirstName(),
            'item' => $aliExpressItem[0],
            'images' => $images
        ]);
    }

    /*
     *
     */
    public function aliExpressToEbay(Request $request)
    {
        $data = $request->request->all();



        return $this->redirectToRoute('dashboard');
//        return $this->render('dashboard/index.html.twig', [
//
//        ]);
    }

}
