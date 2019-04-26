<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile", methods={"GET"})
     */
    public function index()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('profile/index.html.twig', [
            'controller_name' => $user->getFirstName(), 'user' => $user
        ]);
    }

    /**
     * @Route("/profile", name="profileUpdate", methods={"POST"})
     */
    public function update($id)
    {
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found with id '.$userId
            );
        }

        $user->setPlanId($id);

        $date = new DateTime("now");
        $user->setPlanStartTime($date);

        $expDate = clone $date;
        $expDate->modify('+ 30 days');
        $user->setPlanExpireTime($expDate);

        $entityManager->flush();

        return $this->redirectToRoute('success', [
        ]);
    }
}
