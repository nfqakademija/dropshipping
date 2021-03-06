<?php

namespace App\Controller;


use App\AliExpress\AliExpressManager;
use App\AliExpressToEbay\AliExpressToEbayManager;
use App\Entity\AliExpressItem;
use App\Validator\AliExpressToEbayValidator;
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
        try {
            $aliExpressManager->addProduct($data);
            $this->addFlash('success', 'AliExpress product imported succesfully!');
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $aliExpressItems = $this->getDoctrine()->getRepository(AliExpressItem::class)->findBy(['user' => $user, 'active' => true]);

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
    public function editAliExpressItem(int $id, array $data = [], array $errorMessages = [])
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
            'images' => $images,
            'data' => $data,
            'errorMessages' => $errorMessages
        ]);
    }

    /**
     * @param Request $request
     * @param AliExpressToEbayManager $aliExpressToEbayManager
     */
    public function editAliExpressItemPost(Request $request, AliExpressToEbayManager $aliExpressToEbayManager, AliExpressToEbayValidator $validator)
    {
        $url = explode('/', $request->headers->get('referer'));
        $productId = $url[count($url) - 1];

        try {
            $data = $request->request->get('aliExpressProduct');
        } catch (\Exception $e) {
            $this->addFlash('danger', "Wrong input");
            return $this->forward('App\Controller\AliExpressController::editAliExpressItem', [
                'id' => $productId
            ]);
        }

        if (count($validator->validate($request)) === 0) {
            try {
                $aliExpressToEbayManager->addProductToEbay($data);
                $this->addFlash('success', 'AliExpress product added to eBay succesfully!');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->forward('App\Controller\AliExpressController::editAliExpressItem', [
                    'id' => $productId, 'data' => $data
                ]);
            }
        } else {
            return $this->forward('App\Controller\AliExpressController::editAliExpressItem', [
                'id' => $productId, 'data' => $data, 'errorMessages' => $validator->validate($request)
            ]);
        }

        return $this->redirectToRoute('aliExpress');
    }

    public function aliExpressToFrontend(Request $request)
    {
        $productId = $request->query->get('id');
        $aliExpressItem = $this
            ->getDoctrine()
            ->getRepository(AliExpressItem::class)
            ->findBy(['id' => $productId]);

        $imagesArray = [];
        $images = $aliExpressItem[0]->getImages()->getValues();
        foreach ($images as $image) {
            $imagesArray[] = $image->getImageLink();
        }

        $output['title'] = $aliExpressItem[0]->getTitle();
        $output['stock'] = $aliExpressItem[0]->getStock();
        $output['price'] = $aliExpressItem[0]->getPrice();
        $output['description'] = $aliExpressItem[0]->getDescription();
        $output['images'] = $imagesArray;

        return $this->json($output);
    }
}
