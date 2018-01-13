<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\StoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class GamebookController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class GamebookController extends Controller
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @Route("/edit-account", name="account_edit")
     */
    public function editAccount(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        $user= $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password= $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Your credentials have been successfully modified");
            return $this->redirectToRoute("homepage");
        }

        return $this->render('admin/credentials.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
