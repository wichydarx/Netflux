<?php

namespace App\Controller;

use App\Form\UpdateProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateProfileController extends AbstractController
{
    public function __construct(public TranslatorInterface $translator)
    {
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('profile/index.html.twig', [
           'user' => $user
        ]);
    }


    #[Route('/profile/edit', name: 'app_edit_profile')]
    public function edit(Request $request
    , EntityManagerInterface $em
    ): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(UpdateProfileType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', $this->translator->trans('Votre profil a été mis à jour avec succès'));
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
           'user' => $user,
           'form' => $form->createView()
        ]);
    }
    
}
