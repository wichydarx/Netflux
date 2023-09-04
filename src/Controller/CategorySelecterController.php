<?php

namespace App\Controller;

use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorySelecterController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/series', name: 'app_series')]
    public function selectSeries(): Response
    {
        $videos = $this->manager->getRepository(Video::class)->findBy(['category' => 'serie']);;

        return $this->render('category_selecter/series.html.twig', [
            'controller_name' => 'CategorySelecterController',
            'videos' => $videos
        ]);
    }

    public function selectMovies(): Response
    {

        return $this->render('category_selecter/movies.html.twig', [
            'controller_name' => 'CategorySelecterController',
            'videos' => $videos
        ]);
    }
}
