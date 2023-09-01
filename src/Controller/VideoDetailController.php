<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Review;
use App\Entity\Episode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoDetailController extends AbstractController
{
    #[Route('/video/detail/{slug}/{id}', name: 'app_video_detail')]
    public function index($id, EntityManagerInterface $manager): Response
    {

        $video = $manager->getRepository(Video::class)->find($id);
        $review = $manager->getRepository(Review::class)->find($id);
        $episodes = $manager->getRepository(Episode::class)->findBy(['video' => $video]);



        return $this->render('video_detail/index.html.twig', [
            'video' => $video,
            'episodes' => $episodes,
            'review' => $review,
        ]);
    }

    #[Route('/video/detail/{slug}/{id}/like', name: 'app_video_detail_like')]
    public function likeVideo($reviewId, EntityManagerInterface $entityManager): Response
    {
        $review = $entityManager->getRepository(Review::class)->find($reviewId);
        $review->setThumbsUp($review->getThumbsUp() + 1);
        $review->setUser($this->getUser()->getId());
        $entityManager->flush();

        return $this->redirectToRoute('app_video_detail', [
            'id' => $review->getId(),
        ]);
    }

    #[Route('/video/detail/{slug}/{id}/dislike', name: 'app_video_detail_dislike')]
    public function dislikeVideo($reviewId, EntityManagerInterface $entityManager): Response
    {
        $review = $entityManager->getRepository(Review::class)->find($reviewId);
        $review->setDislike($review->getDislike() + 1);
        $review->setUser($this->getUser()->getId());
        $entityManager->flush();

        return $this->redirectToRoute('app_video_detail', [
            'id' => $review->getId(),
        ]);
    }
}
