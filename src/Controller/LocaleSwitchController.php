<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocaleSwitchController extends AbstractController
{

    #[Route('/locale/{_locale}', name: 'switch_language', requirements: ['_locale' => 'en|fr'])]

    public function changeLocale($_locale, Request $request)
    {
        $request->getSession()->set('_locale', $_locale);
        return $this->redirect($request->headers->get('referer'));
    }
}
