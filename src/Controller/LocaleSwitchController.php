<?php

namespace App\Controller;

use App\Service\Translate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\LocaleSwitcher;

class LocaleSwitchController extends AbstractController
{
    public function __construct(public Translate $translate)
    { }

    #[Route('/locale/{_locale}', name: 'app_locale_switch', requirements: ['_locale' => 'en|fr'])]
    public function switch(string $_locale, Request $request): Response
    {

        if (in_array($_locale, ['en', 'fr'])) {

            $request->getSession()->set('_locale', $_locale);

            $this->translate->trans($_locale);
  
        }


        return $this->redirect($request->headers->get('referer', $this->generateUrl('app_home')));
    }

}
