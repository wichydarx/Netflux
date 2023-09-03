<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Video;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/search', name: 'app_search')]
    public function search(Request $request, EntityManagerInterface $em): Response
  {
    $title = $request->request->get('search');
    $videos = $em->getRepository(Video::class)->searchByTitle($title);
    
    return $this->render('home/search.html.twig', [
      'videos' => $videos,
      'title' => $title
    ]);
  }
    #[Route('/api/videos', name: 'app_movie')]
    public function test(VideoRepository $videoRepository)
    {

        return $this->json($videoRepository->findAll(), 200, [], []);
    }


    #[Route('/series', name: 'app_series')]
    public function findBySeries(): Response
    {
        $series = $this->manager->getRepository(Video::class)->findBy(['category' => 'serie']);

        return $this->render('home/series.html.twig', [
          'series' => $series,
        ]);
    }
    
    #[Route('/films', name: 'app_films')]
    public function findByFilms(): Response
    {
        $films = $this->manager->getRepository(Video::class)->findBy(['category' => 'film']);

        return $this->render('home/films.html.twig', [
          'films' => $films,
        ]);
    }

}
