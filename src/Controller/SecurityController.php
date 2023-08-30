<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MailJet;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Form\ResetPasswordRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private  TokenGeneratorInterface $tokenGenerator,
        private MailJet $mailJet
    )
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    #[Route(path: '/reset_password', name: 'app_reset_password_request')]
    public function resetPasswordRequest(Request $request,): Response
    {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if (!$user) {
                $this->addFlash('danger', 'Une erreur est survenue ');
                return $this->redirectToRoute('app_login');
            }
            $token = $this->tokenGenerator->generateToken();
            try {
                $user->setToken($token);
                $this->entityManager->flush();
                $url = $this->generateUrl('app_reset_password', ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL);
                $this->mailJet->sendEmailToUser($user, 'Réinitialisation de mot de passe', $url);
                $this->addFlash('success', 'Un email de réinitialisation de mot de passe vous a été envoyé');
                return $this->redirectToRoute('app_login');
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Une erreur est survenue : ' . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }
        
        }
        return $this->render('security/reset_password_request.html.twig',[
            "form" => $form->createView()
        ]);
    }

    #[Route(path: '/reset_password/{token}', name: 'app_reset_password')]
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
    )
    {
        $user = $userRepository->findOneBy(['token' => $token]);

        if ($user) {
            $form = $this->createForm(ResetPasswordType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setToken(null);
                $user->setPassword(
                    $this->passwordHasher->hashPassword(
                        $user,
                        $form->getData()['password']
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'form' => $form->createView()
            ]);
        }
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
        return $this->render('security/reset_password.html.twig');
    }
}



