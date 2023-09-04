<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Review;
use App\Entity\Episode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoDetailController extends AbstractController
{
    #[Route('/video/detail/{slug}/{id}', name: 'app_video_detail')]
    public function index($id, EntityManagerInterface $manager): Response
    {

        $video = $manager->getRepository(Video::class)->find($id);
        $review = $manager->getRepository(Review::class)->find($id);
        $episodes = $manager->getRepository(Episode::class)->findBy(['video' => $video]);
        $totalLikes = $manager->getRepository(Review::class)->count(['video_id' => $id, 'thumbsUp' => 1]);
        $totalDislikes = $manager->getRepository(Review::class)->count(['video_id' => $id, 'dislike' => 1]);
        $totalComments = $manager->getRepository(Review::class)->countCommentsByVideoId($id);
        $comments = $manager->getRepository(Review::class)->findCommentsByVideoId($id);



        return $this->render('video_detail/index.html.twig', [
            'video' => $video,
            'episodes' => $episodes,

            'totalLikes' => $totalLikes,
            'totalDislikes' => $totalDislikes,
            'totalComments' => $totalComments,
            'comments' => $comments
        ]);
    }

    #[Route('/video/like/{id}', name: 'app_video_like')]
    public function likeVideo($id, EntityManagerInterface $entityManager): Response
    {
        $video = $entityManager->getRepository(Video::class)->find($id);
        $user = $this->getUser();

        $reviewExist = $entityManager->getRepository(Review::class)->findOneBy(['user_id' => $user, 'video_id' => $video->getId()]);

        if ($reviewExist) {
            if ($reviewExist->getThumbsUp() === 0 && $reviewExist->getDislike() === 0) {
                $reviewExist->setThumbsUp(1);
            } else {
                $reviewExist->setThumbsUp(0);
                $reviewExist->setDislike(0);
            }
        } else {
            $review = new Review();
            $review->setVideoId($video);
            $review->setUserId($user);
            $review->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $review->setThumbsUp(1);

            $entityManager->persist($review);
        }
        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $this->redirect(
            $this->generateUrl(
                'app_video_detail',
                ['slug' => $video->getSlug(), 'id' => $video->getId()]
            )
        );
    }

    #[Route('/video/dislike/{id}', name: 'app_video_dislike')]
    public function dislikeVideo($id, EntityManagerInterface $entityManager): Response
    {
        $video = $entityManager->getRepository(Video::class)->find($id);
        $user_id = $this->getUser();

        $reviewExist = $entityManager->getRepository(Review::class)->findOneBy(['user_id' => $user_id, 'video_id' => $video->getId()]);

        if ($reviewExist) {
            if ($reviewExist->getDislike() === 0 && $reviewExist->getThumbsUp() === 0) {
                $reviewExist->setDislike(1);
            } else {
                $reviewExist->setDislike(0);
                $reviewExist->setThumbsUp(0);
            }
        } else {
            $review = new Review();
            $review->setVideoId($video);
            $review->setUserId($user_id);
            $review->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $review->setDislike(1);

            $entityManager->persist($review);
        }
        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $this->redirect(
            $this->generateUrl(
                'app_video_detail',
                ['slug' => $video->getSlug(), 'id' => $video->getId()]
            )
        );
    }

    #[Route('/video/comment/{id}', name: 'app_video_comment')]
    public function commentVideo(
        $id,
        EntityManagerInterface $entityManager,
        Request $request,
        TranslatorInterface $translator
    ): Response {
        $video = $entityManager->getRepository(Video::class)->find($id);
        $user = $this->getUser();
        $comment = $request->request->get('comment');
        if ($comment === null || $comment === '') {
            $this->addFlash('error', $translator->trans('Vous ne pouvez pas envoyer un commentaire vide'));
            return $this->redirect(
                $this->generateUrl(
                    'app_video_detail',
                    ['slug' => $video->getSlug(), 'id' => $video->getId()]
                )
            );
        }
        $review = new Review();
        $review->setVideoId($video);
        $review->setUserId($user);
        $review->setIsEdited(false);
        $review->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $review->setComment($comment);
        $entityManager->persist($review);
        $entityManager->flush();

        return $this->redirect(
            $this->generateUrl(
                'app_video_detail',
                ['slug' => $video->getSlug(), 'id' => $video->getId()]
            )
        );
    }

    //comment delete
    #[Route('/video/comment/delete/{id}', name: 'app_video_comment_delete')]
    public function deleteComment($id, EntityManagerInterface $entityManager): Response
    {
        $review = $entityManager->getRepository(Review::class)->findUserComment($id, $this->getUser());
        $video = $review->getVideoId();
        $entityManager->remove($review);
        $entityManager->flush();

        return $this->redirect(
            $this->generateUrl(
                'app_video_detail',
                ['slug' => $video->getSlug(), 'id' => $video->getId()]
            )
        );
    }

    //comment edit
    #[Route('/video/comment/edit/{id}', name: 'app_video_comment_edit')]
    public function editComment($id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $review = $entityManager->getRepository(Review::class)->findUserComment($id, $this->getUser());
        $video = $review->getVideoId();
        $comment = $request->request->get('editedComment');
        $review->setComment($comment);
        $review->setIsEdited(true);
        $entityManager->flush();

        return $this->redirect(
            $this->generateUrl(
                'app_video_detail',
                ['slug' => $video->getSlug(), 'id' => $video->getId()]
            )
        );
    }
}
