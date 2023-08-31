<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        $videos = $this->manager->getRepository(Video::class)->findAll();
        $genres = $this->manager->getRepository(Genre::class)->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'videos' => $videos,
            'genres' => $genres
        ]);
    }
}
