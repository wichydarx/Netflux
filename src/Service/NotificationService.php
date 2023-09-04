<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Notifications;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function sendNotification(string $subject, string $content, User $user): void
    {
        $notification = new Notifications();
        $notification->setSubject($subject);
        $notification->setContent($content);
        $notification->setReceiver($user);
        $this->em->persist($notification);
        $this->em->flush();
    }

    public function removeAllNotificationsForUser(User $user): void
    {

        $notifications = $this->em->getRepository(Notifications::class)->findBy(['receiver' => $user]);


        foreach ($notifications as $notification) {
            $this->em->remove($notification);
        }

        $this->em->flush();
    }

}
