<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\User;
use App\Form\ProfileFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile", methods={"GET", "POST"})
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(ProfileFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $updateUser = $entityManager->getRepository(User::class)->find($user->getId());
            $data = $form->getData();
            $firstName = $data->getFirstName();
            $lastName = $data->getLastName();
            $ebayCountry = $data->getEbayCountry();
            $password =  $passwordEncoder->encodePassword($data, $data->getPassword());

            if (!empty($firstName)) $updateUser->setFirstName($firstName);
            if (!empty($lastName)) $updateUser->setLastName($lastName);
            if (!empty($ebayCountry)) $updateUser->setEbayCountry($ebayCountry);
            if (!empty($password)) $updateUser->setPassword($password);

            $entityManager->flush();

            return $this->redirectToRoute('profile');
        }

        $plan = "You don't have a plan";
        if ($user->getPlanId()) {
            $plan = $this->getDoctrine()->getRepository(Plan::class)->find($user->getPlanId())->getName();
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => $user->getFirstName(), 'user' => $user, 'profileForm' => $form->createView(), 'plan' => $plan
        ]);
    }


}
