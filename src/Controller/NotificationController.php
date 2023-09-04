<?php

namespace App\Controller;

use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotificationController extends AbstractController
{



    #[Route('/notifications/remove', name: 'app_notifications_remove')]
    public function remove(
        NotificationService $notif,
        Request $request
    ): Response
    {
        $user = $this->getUser();
        $notif->removeAllNotificationsForUser($user);
        return $this->redirect($request->headers->get('referer'));
    }
    
}
