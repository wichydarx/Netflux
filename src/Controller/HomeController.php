<?php

namespace App\Controller;

use App\Entity\Video;
use App\Repository\VideoRepository;
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

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'videos' => $videos
        ]);
    }

    #[Route('/api/videos', name: 'app_movie')]
    public function test(VideoRepository $videoRepository)
    {

        return $this->json($videoRepository->findAll(), 200, [], []);
    }
}
