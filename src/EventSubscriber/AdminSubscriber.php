<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Video;
use App\Service\NotificationService;
use Symfony\Component\Asset\Packages;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        public EntityManagerInterface $em,
        public NotificationService $notif,
        private UrlGeneratorInterface $urlGenerator,
        private Packages $assetPackages
    ) {
    }

    public function onBeforePersistedEvent(AfterEntityPersistedEvent $event): void
    {
        /** @var Video $video */
        $video = $event->getEntityInstance();
        if (!($video instanceof Video)) {
            return;
        }
        $date = new \DateTime("now", new \DateTimeZone('Europe/Paris'));
        $dateString = $date->format('Y-m-d H:i:s');

        if ($video->getCategory() == "serie") {
            $subject = " Nouvelle série/épisode";
        } else {
            $subject = "Nouveau film";
        }

        // Construct HTML content outside of the string
        $content = "<li>
            <div class='row'>
                <div class='col-md'>
                    <a href='" . $this->generateVideoDetailUrl($video) . "'>
                        <img src='" . $this->generateThumbnailUrl($video) . "' class='img-fluid' alt='" . $video->getTitle() . "'>
                    </a>
                </div>
                <div class='col-md'>
                    <p class='h6 mb-0'>" . $subject . "</p>
                    <span class='small'>" . $dateString . "</span>
                </div>
            </div>
        </li>";

        // Send notifications to users
        $users = $this->em->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $this->notif->sendNotification($subject, $content, $user);
        }
    }

    private function generateVideoDetailUrl(Video $video): string
    {
        // Generate the URL for video detail page using UrlGeneratorInterface
        return $this->urlGenerator->generate('app_video_detail', ['slug' => $video->getSlug(), 'id' => $video->getId()]);
    }

    private function generateThumbnailUrl(Video $video): string
    {
        // Generate the URL for the video's thumbnail image using AssetPackages
        return $this->assetPackages->getUrl('uploads/thumbnail/' . $video->getThumbnail());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => 'onBeforePersistedEvent',
        ];
    }
}
