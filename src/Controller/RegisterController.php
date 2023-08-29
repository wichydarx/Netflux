<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{

    private $passwordHash;
    private $fileUploader;

    public function __construct(UserPasswordHasherInterface $passwordHash, FileUploader $fileUploader)
    {
        $this->passwordHash = $passwordHash;
        $this->fileUploader = $fileUploader;
    }

    #[Route('/register', name: 'app_register')]
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $passwordClear = $user->getPassword();
            // Fonction pour hashé le mot de passe
            $passwordIsHashed = $this->passwordHash->hashPassword($user, $passwordClear);
            // Stockage du mot de passe hashé dans l'entité User
            $user->setPassword($passwordIsHashed);

            $avatar = $form->get('avatar')->getData();
            // Si le champ avatar est rempli alors on utilise le service FileUploader afin d'upload l'image
            if ($avatar) {
                $newFilename = $this->fileUploader->upload($avatar);
                $user->setAvatar($newFilename);
            }

            // Ajout des informations de l'entité User dans la base de données
            $this->manager->persist($user);
            $this->manager->flush();
        }
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'registerForm' => $form->createView()
        ]);
    }
}
