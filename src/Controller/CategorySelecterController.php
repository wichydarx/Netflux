<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorySelecterController extends AbstractController
{
    #[Route('/series', name: 'app_series')]
    public function selectSeries(): Response
    {


        return $this->render('category_selecter/series.html.twig', [
            'controller_name' => 'CategorySelecterController',
        ]);
    }

    public function selectMovies(): Response
    {

        return $this->render('category_selecter/index.html.twig', [
            'controller_name' => 'CategorySelecterController',
        ]);
    }
}
