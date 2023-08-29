<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{


    public function __construct()
    {
    }

    #[Route('/register', name: 'app_register')]
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd('valide');
        }
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'registerForm' => $form->createView()
        ]);
    }
}
